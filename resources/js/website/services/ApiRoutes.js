const ApiVersion = "/api/v1.0";
const ApiRoutes = {
    Login: ApiVersion + '/auth/login',
    Register: ApiVersion + '/auth/registration',
    Forgot: ApiVersion + '/auth/forgot/password',
    Reset: ApiVersion + '/auth/reset/password',
    Verify: ApiVersion + '/auth/verify/account',
    LoginFacebook: ApiVersion + '/auth/social/login',
};
export default ApiRoutes;
