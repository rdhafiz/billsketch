import axios from 'axios'

let headers = {
    'Content-Type': 'application/json; charset=utf-8',
};
const ApiService = {
    POST: (url, param, callback) => {
        axios.post(url, param, {headers: headers}).then((response) => {
            if (response.status === 200) {
                callback(response.data);
            }
        }).catch(err => {
            const error_code = parseInt(err.toLocaleString().replace(/\D/g, ""));
            if (error_code === 401) {
            }
        })
    },
    GET: (url, callback) => {
        axios.get(url, {headers: headers}).then((response) => {
            if (response.status === 200) {
                callback(response.data);
            }
        }).catch(err => {
            const error_code = parseInt(err.toLocaleString().replace(/\D/g, ""));
            if (error_code === 401) {
            }
        })
    },
    ErrorHandler(errors) {
        $('.is-invalid').removeClass('is-invalid');
        $('.error-report').html('');
        $('.error-report-g').html('');
        $.each(errors, (i, v) => {
            if (i === 'error') {
                $('.error-report-g').html('<p class="alert alert-danger">' + v + '</p>')
            } else {
                $('[name=' + i + ']').addClass('is-invalid');
                $('[name=' + i + ']').closest('.form-group').find('.error-report').html(v);
            }
        });
    },
    ClearErrorHandler() {
        $('.is-invalid').removeClass('is-invalid');
        $('.error-report').html('');
    },
    setAuthentication: (access_token, user, callback) => {
        let date = new Date();
        date.setTime(date.getTime() + (2*24*60*60*1000));
        const token = "BilifyAccessToken=" + access_token;
        const info = "BilifyUserInfo=" + JSON.stringify(user);
        const expires = "; expires=" + date.toUTCString();
        document.cookie = token + expires + "; path=/";
        document.cookie = info + expires + "; path=/";
        callback(true);
    }
}
export default ApiService;
