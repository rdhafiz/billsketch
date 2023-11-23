<template>
    <Register_banner></Register_banner>

    <div class="floating-section w-100">
        <div class="container-lg">
            <div class="floating-section-content w-100">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="card shadow-lg border border-2 border-primary">
                            <div class="card-body px-3 px-sm-5 py-5">
                                <div class="row">
                                    <div class="col-xl-6 d-none d-xl-block">
                                        <div class="auth_bg">
                                            <img :src="'/assets/images/auth_bg.jpg'" alt="auth images">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="w-100">


                                            <div class="form-group mb-3">
                                                <button class="btn btn-lg btn-primary w-100"><i class="fa fa-fw fa-facebook"></i> Sign Up with Facebook</button>
                                            </div>
                                            <div class="form-group mb-3">
                                                <button class="btn btn-lg btn-danger w-100"><i class="fa fa-fw fa-google-plus"></i> Sign Up with Google</button>
                                            </div>
                                            <div class="form-group my-4 text-center">
                                                --- OR ---
                                            </div>

                                            <form class="w-100" action="">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="first_name"><strong>First Name</strong></label>
                                                            <input type="text" class="form-control form-control-lg" id="first_name" name="first_name" placeholder="First Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="last_name"><strong>Last Name</strong></label>
                                                            <input type="text" class="form-control form-control-lg" id="last_name" name="last_name" placeholder="Last Name" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="email"><strong>Email</strong></label>
                                                    <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="user_type"><strong>User Type</strong></label>
                                                    <select name="user_type" id="user_type" class="form-control form-control-lg" v-model="formData.user_type">
                                                        <option value="" disabled>Select</option>
                                                        <option value="1">Individual</option>
                                                        <option value="2">Company</option>
                                                    </select>
                                                </div>
                                               <template v-if="formData.user_type == 2">
                                                   <div class="form-group mb-3">
                                                       <label class="form-label" for="company_name"><strong>Company Name</strong></label>
                                                       <input type="text" class="form-control form-control-lg" id="company_name" name="company_name" placeholder="Company Name" required>
                                                   </div>
                                                   <div class="form-group mb-3">
                                                       <label class="form-label" for="company_size"><strong>Company Size</strong></label>
                                                       <input type="text" class="form-control form-control-lg" id="company_size" name="company_size" placeholder="Company Size" required>
                                                   </div>
                                                   <div class="form-group mb-3">
                                                       <label class="form-label" for="company_address"><strong>Company Address</strong></label>
                                                       <input type="text" class="form-control form-control-lg" id="company_address" name="company_address" placeholder="Company Address" required>
                                                   </div>
                                                   <div class="form-group mb-3">
                                                       <label class="form-label" for="company_city"><strong>Company City</strong></label>
                                                       <input type="text" class="form-control form-control-lg" id="company_city" name="company_city" placeholder="Company City" required>
                                                   </div>
                                                   <div class="form-group mb-3">
                                                       <label class="form-label" for="company_country"><strong>Company Country</strong></label>
                                                       <input type="text" class="form-control form-control-lg" id="company_country" name="company_country" placeholder="Company Country" required>
                                                   </div>
                                               </template>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="password">
                                                                <strong>Password</strong>
                                                            </label>
                                                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="password_confirmation">
                                                                <strong>Confirm Password</strong>
                                                            </label>
                                                            <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <button class="btn btn-lg btn-success w-100"><i class="fa fa-fw fa-send"></i> Sign Up</button>
                                                </div>
                                                <div class="form-group">
                                                    <div class="w-100 text-center">
                                                        Already have an account?
                                                        <router-link :to="{name: 'Login'}">Sign In</router-link>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>
<script>
import Register_banner from "./widgets/register_banner.vue";
export default {
    components: {
        Register_banner
    },
    data(){
        return {
            formData : {
                first_name: '',
                user_type: ''
            }
        }
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
