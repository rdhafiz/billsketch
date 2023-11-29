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

    // Employee
    employeeCreate: ApiVersion + '/employee/save',
    employeeList: ApiVersion + '/employee/list',
    employeeSingle: ApiVersion + '/employee/single',
    employeeStatus: ApiVersion + '/employee/update/status',
    employeeUpdate: ApiVersion + '/employee/update',
    employeeDelete: ApiVersion + '/employee/delete',

    // Category
    categoryCreate: ApiVersion + '/category/save',
    categoryList: ApiVersion + '/category/list',
    categorySingle: ApiVersion + '/category/single',
    categoryStatus: ApiVersion + '/category/update/status',
    categoryUpdate: ApiVersion + '/category/update',
    categoryDelete: ApiVersion + '/category/delete',
};
export default ApiRoutes;
