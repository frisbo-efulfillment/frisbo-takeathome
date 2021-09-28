import axios from 'axios';
import qs from 'qs';

class Http {
    constructor() {
        axios.defaults.headers.patch['Content-Type'] = 'application/json';
        axios.interceptors.request.use((request) => {
            if (request.data && request.headers['Content-Type'] === 'application/json') {
                request.data = qs.stringify(request.data);
            }

            return request;
        });
        this._axios = axios.create({});
        this._url = 'http://localhost:3000';
        this._route = false;
        this._response = false;
    }

    static _getAuthConfig() {
        let axiosConfig = {};

        const token = sessionStorage.getItem('token');

        if (token) {
            axiosConfig = Object.assign({}, {
                headers: {'Authorization': 'Bearer ' + token}
            });
        }

        return axiosConfig;
    }

    _validate() {
        if (!this._route) {
            throw new Error('Method was not specified.');
        }
    }

    route(apiMethod) {
        this._route = apiMethod;

        return this;
    }

    build() {
        let response = this._response && this._response.data ? {...this._response.data} : false;
        this._response = false;
        let isError = false;
        let errorMessages = {};
        let data = false;
        let pagination = false;

        if (response) {
            if (response.isError) {
                isError = true;
            } else {
                data = response;
            }
        } else {
            console.log('No response');
        }

        return {
            isError,
            errorMessages,
            data,
            pagination
        };
    }

    async get(options = {}) {
        this._validate();

        let url = `${this._url}/${this._route}`;
        this._route = false;

        let authConfig = Http._getAuthConfig();

        try {
            this._response = await this._axios.get(url, {
                params: {
                    ...options,
                },
                ...authConfig
            });
        } catch (e) {
            console.log(e);
        }

        return this.build();
    }

    async post(data = {}) {
        this._validate();

        let url = `${this._url}/${this._route}`;
        this._route = false;

        let authConfig = Http._getAuthConfig();

        try {
            this._response = await this._axios.post(url, data, authConfig);
        } catch (e) {
            console.log(e);
        }

        return this.build();
    }

    async delete(data = {}) {
        this._validate();

        let url = `${this._url}/${this._route}`;
        this._route = false;

        let authConfig = Http._getAuthConfig();

        try {
            this._response = await this._axios.delete(url, {...authConfig, params: {...data}});
        } catch (e) {
            console.log(e);
        }

        return this.build();
    }

    async put(data = {}) {
        this._validate();

        let url = `${this._url}/${this._route}`;
        this._route = false;

        let authConfig = Http._getAuthConfig();

        try {
            this._response = await this._axios.put(url, {
                ...data,
            }, authConfig);
        } catch (e) {
            console.log(e);
        }

        return this.build();
    }

    async patch(data = {}) {
        this._validate();

        let url = `${this._url}/${this._route}`;
        this._route = false;

        let authConfig = Http._getAuthConfig();

        try {
            this._response = await this._axios.patch(url, {
                ...data,
            }, authConfig);
        } catch (e) {
            console.log(e);
        }

        return this.build();
    }
}

export default new Http();
