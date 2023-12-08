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

    // Invoice
    invoiceCreate: ApiVersion + '/invoice/save',
    invoiceUpdate: ApiVersion + '/invoice/update',
    invoiceList: ApiVersion + '/invoice/list',
    invoiceSingle: ApiVersion + '/invoice/single',
    invoiceStatusList: ApiVersion + '/invoice/get/status',
    invoiceRecurring: ApiVersion + '/invoice/get/recurring',
    invoiceNumber: ApiVersion + '/invoice/get/number',
    invoiceActivity: ApiVersion + '/invoice/update/activity',
    invoiceDelete: ApiVersion + '/invoice/delete',
    invoiceDownload: ApiVersion + '/invoice/download',
    invoiceShare: ApiVersion + '/invoice/share',
    invoiceQRCode: ApiVersion + '/invoice/generate/qrcode',
    invoiceStatusUpdate: ApiVersion + '/invoice/update/status',

};
export default ApiRoutes;
