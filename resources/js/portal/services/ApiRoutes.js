const ApiVersion = "/api/v1.0";
const ApiRoutes = {
    // Profile
    profile: ApiVersion + '/profile/get',
    updateProfile: ApiVersion + '/profile/update',
    changePassword: ApiVersion + '/profile/update/password',
    profile_logout: ApiVersion + '/profile/logout',

    // Client
    clientCreate: ApiVersion + '/client/save',
    clientList: ApiVersion + '/client/list',
    clientSingle: ApiVersion + '/client/single',
    clientStatus: ApiVersion + '/client/update/status',
    clientUpdate: ApiVersion + '/client/update',
    clientDelete: ApiVersion + '/client/delete',
};
export default ApiRoutes;
