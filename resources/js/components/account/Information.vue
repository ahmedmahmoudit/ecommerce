<template>
    <div>
        <my-account-banner
            :bannerTitle="$t('pages.myInfo')"
        ></my-account-banner>

        <div class="container innr-cont-area">
            <div class="row">
                <my-account-sidebar></my-account-sidebar>

                <!--/ Content Here-->
                <div class="col-sm-9 right-sec addrss-book">

                    <h4>{{$t('pages.editAccountInformation')}}</h4>
                    <div class="add-newaddrss">
                        <form class="row">
                            <div v-if="account.type == '1'">
                                <div class="col-sm-6">
                                    <div> {{$t('pages.firstName')}} </div>
                                    <input type="text" class="form-control rounded-0" :placeholder="$t('pages.firstName')" name="first_name" v-model="account.first_name">
                                </div><!--/.col-sm-6-->
                                <div class="col-sm-6">
                                    <div> {{$t('pages.lastName')}} </div>
                                    <input type="text" class="form-control rounded-0" :placeholder="$t('pages.lastName')" name="last_name" v-model="account.last_name">
                                </div><!--/.col-sm-6-->
                            </div>
                            <div v-else>
                                <div class="col-sm-6">
                                    <div> {{$t('pages.company')}} </div>
                                    <input type="text" class="form-control rounded-0" :placeholder="$t('pages.company')" name="company"v-model="account.company">
                                </div><!--/.col-sm-6-->
                                <div class="col-sm-6">
                                    <div> {{$t('pages.contactPerson')}} </div>
                                    <input type="text" class="form-control rounded-0" :placeholder="$t('pages.contactPerson')" name="contact_person" v-model="account.contact_person">
                                </div><!--/.col-sm-6-->
                                <div class="col-sm-6">
                                    <div> {{$t('pages.jobTitle')}} </div>
                                    <input type="text" class="form-control rounded-0" :placeholder="$t('pages.jobTitle')" name="job_title" v-model="account.job_title">
                                </div><!--/.col-sm-6-->
                                <div class="col-sm-6">
                                    <div> {{$t('pages.companyLicense')}} </div>
                                    <a :href="'/'+account.company_license" target="_blank" v-if="account.company_license" style="color: #c80412;"> {{$t('pages.viewFile')}} </a>
                                    <input type="file" class="form-control rounded-0" name="file" ref="file" @change="handleFileUpload($event)">
                                </div><!--/.col-sm-6-->
                            </div>
                            <div class="col-sm-6">
                                <div> {{$t('pages.emailAddress')}} </div>
                                <input type="text" class="form-control rounded-0" :placeholder="$t('pages.emailAddress')" name="email" v-model="account.email">
                            </div><!--/.col-sm-6-->
                            <div class="col-sm-6 ">
                                <div> {{$t('pages.phone')}} </div>
                                <div class="phonewithcontry">
                                    <select class="form-control contry">
                                        <option>+965</option>
                                    </select>
                                    <input type="number" class="form-control rounded-0 phone" :placeholder="$t('pages.phone')" name="phone" v-model="account.phone">
                                </div>
                            </div><!--/.col-sm-6-->


                            <div class="col-sm-12 mt-30">
                                <button class="btn-lg btn-success rounded-0" @click.prevent="updateAccount">{{$t('pages.updateAccountInfo')}}</button>
                            </div><!--/.col-sm-12-->
                        </form>
                    </div><!--/.row-->
                </div><!--/.col-sm-9-->
            </div>
        </div><!--/.innr-cont-area-->
    </div>
</template>

<script>
    import myAccountSidebar from "./partials/TheSidebar";
    import myAccountBanner from "./partials/TheBanner";
    export default {
        components: {myAccountSidebar, myAccountBanner},
        data(){
            return {
                account: {
                    file: ''
                }
            }
        },
        mounted() {
            if (this.$store.getters['authModule/isAuthenticated']) {
                console.log('sending authorization while  browser refresh');

                axios.get(
                    '/api/v1/account/info',
                    {headers: {
                            "Authorization" : `Bearer ${this.$store.state.authModule.accessToken}`
                        }
                    }
                ).then((response) => {
                        this.account = response.data;
                    });
            }else{
                console.log('No authorization');
            }

        },
        methods: {
            handleFileUpload(event){
                this.account.file = this.$refs.file.files[0];
            },
            updateAccount(){

                //Initialize the form data
                let formData = new FormData();
                // upload file
                formData.append('file', this.account.file);
                formData.append('first_name', this.account.first_name);
                formData.append('last_name', this.account.last_name);
                formData.append('company', this.account.company);
                formData.append('contact_person', this.account.contact_person);
                formData.append('job_title', this.account.job_title);
                formData.append('phone', this.account.phone);
                formData.append('email', this.account.email);
                /*
                  Make the request to the POST /single-file URL
                */
                let  _this = this;

                axios.post('/api/v1/account/info',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function (response) {
                    if(response.data.message) {
                        _this.$swal({
                            icon: 'success',
                            title: 'Account Updated!',
                            text: 'Your account information updated successfully',
                        });
                    }

                }).catch(function (errors) {
                    console.log(errors);
                     _this.$swal({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Email is already registered!',
                    });
                });
            }
        }
    }
</script>
