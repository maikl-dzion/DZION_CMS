
const serverUrl = 'http://home.ru/DZION_CMS/api';
// const serverUrl = 'http://bolderfest.ru/DZION_CMS/api';

const HttpServiceAsync = {

    data() {
        return {
            serverUrl: serverUrl,
            httErrorInfo: {},
        }
    },

    methods: {

        httpErrorHandler(code, message, url, comment) {
            this.httErrorInfo = { code, url, comment, message };
            console.log(this.httErrorInfo);
            alert(comment);
        },

        async send(url, postData = {}, method = 'get') {

            const urlLine = '===== Url:' + url + '======';
            console.log(urlLine);
            let result = {};
            let response = {};
            let apiUrl = this.serverUrl + url;

            try {
                switch (method) {
                    case 'post':
                    case 'put':
                        response = await axios[method](apiUrl, postData);
                        break;
                    default:
                        response = await axios[method](apiUrl);
                        break;
                }
            } catch (error) {
                this.httpErrorHandler(1, error, apiUrl, 'Ошибка при выполнении запроса');
                return false;
            }

            if (response.data instanceof Object) {
                result = response.data;
                return result;
            }

            this.httpErrorHandler(2, response.data, apiUrl, 'Произошла ошибка на стороне сервера');
            return false;
        }

    }
};

const HttpService = {

    data() {
        return {
            serverUrl: serverUrl,
            httErrorInfo: {},
        }
    },

    methods: {

        send(url, postData = {}, method = 'get') {

            const query = '===== Url:' + url + '======';
            const errorMessage = 'Ошибка cath в axios ' + query;
            console.log(query);

            var apiUrl = this.serverUrl + url;
            var response = {};
            var result = {};

            return new Promise((resolve, reject) => {

                switch (method) {
                    case 'post':
                    case 'put':
                        axios[method](apiUrl, postData)
                            .then(response => {
                                if (response.data instanceof Object) {
                                    console.log('---- OK -----');
                                    resolve(response.data);
                                } else {
                                    this.httpErrorHandler(1, response.data, apiUrl, 'Произошла ошибка на стороне сервера');
                                    reject(response.data);
                                }
                            }).catch(error => {
                                this.httpErrorHandler(2, error, apiUrl, errorMessage);
                                reject(error);
                            });
                        break;

                    default:
                        axios[method](apiUrl)
                            .then(response => {
                                if (response.data instanceof Object) {
                                    console.log('---- OK -----');
                                    resolve(response.data);
                                } else {
                                    this.httpErrorHandler(1, response.data, apiUrl, 'Произошла ошибка на стороне сервера');
                                    reject(response.data);
                                }
                            }).catch(error => {
                                this.httpErrorHandler(2, error, apiUrl, errorMessage);
                                reject(error);
                            });
                        break;
                }

            });  // return promise

        }, // send function

        httpErrorHandler(code, message, url, comment) {
            this.httErrorInfo = { code, url, comment, message };
            console.log(this.httErrorInfo);
            alert(comment);
        },

        errorShow(message, error) {
            console.log('---- ERROR -----', error);
            alert(message);
        }

    } // methods
}