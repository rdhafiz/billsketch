const AuthService = {
    getAccessToken: () => {
        let BilifyAccessToken = null;
        let cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            let c = cookies[i].trim();
            if (c.includes('BilifyAccessToken')) {
                BilifyAccessToken = c.replace('BilifyAccessToken=', '');
            }
        }
        return "Bearer " + BilifyAccessToken;
    },
    logout: (callback) => {
        document.cookie = "BilifyAccessToken=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "BilifyUserInfo=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        callback(true);
    }
}
export default AuthService;
