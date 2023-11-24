const ApiVersion = "/api/v1.0";
const ApiRoutes = {
    // Profile
    profile: ApiVersion + '/profile/get',
    updateProfile: ApiVersion + '/profile/update',
    changePassword: ApiVersion + '/profile/update/password',
    profile_logout: ApiVersion + '/profile/logout',
};
export default ApiRoutes;
