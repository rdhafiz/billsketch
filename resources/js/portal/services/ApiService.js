import axios from 'axios'
import AuthService from "./AuthService";

let headers = {
    'Content-Type': 'application/json; charset=utf-8',
    'X-Authorization': ''
};
const ApiService = {
    POST: (url, param, callback) => {
        headers['X-Authorization'] = AuthService.getAccessToken();
        axios.post(url, param, {headers: headers}).then((response) => {
            if (response.status === 200) {
                callback(response.data);
            }
        }).catch(err => {
            const error_code = parseInt(err.toLocaleString().replace(/\D/g, ""));
            if (error_code === 401) {
                AuthService.logout(() => {

                })
            }
        })
    },
    POST_FORMDATA: (url, param, callback) => {
        headers['Content-Type'] = 'multipart/form-data';
        headers['X-Authorization'] = AuthService.getAccessToken();
        axios.post(url, param, {headers: headers}).then((response) => {
            if (response.status === 200) {
                callback(response.data);
            }
        }).catch(err => {
            const error_code = parseInt(err.toLocaleString().replace(/\D/g, ""));
            if (error_code === 401) {
                AuthService.logout(() => {

                })
            }
        })
    },
    GET: (url, callback) => {
        headers['X-Authorization'] = AuthService.getAccessToken();
        axios.get(url, {headers: headers}).then((response) => {
            if (response.status === 200) {
                callback(response.data);
            }
        }).catch(err => {
            const error_code = parseInt(err.toLocaleString().replace(/\D/g, ""));
            if (error_code === 401) {
                AuthService.logout(() => {

                })
            }
        })
    },
    ErrorHandler(errors) {
        console.log(errors)
        $('.is-invalid').removeClass('is-invalid');
        $('.error-report').html('');
        $('.error-report-g').html('');
        $.each(errors, (i, v) => {
            console.log(i)
            console.log($('[name=' + i + ']'))
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
    }
}
export default ApiService;
