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
        $('.is-invalid').removeClass('is-invalid');
        $('.error-report').html('');
        $('.error-report-g').html('');
        Object.entries(errors).forEach(([i, v]) => {
            const inputElement = document.querySelector(`[name="${i}"]`);
            const invalidFeedback = inputElement?.closest('.form-group')?.querySelector('.error-report');
            if (invalidFeedback) {
                invalidFeedback.textContent = v;
                inputElement.classList.add('is-invalid')
            }
        });
    },
    ClearErrorHandler() {
        $('.is-invalid').removeClass('is-invalid');
        $('.error-report').html('');
    }
}
export default ApiService;
