import axios from 'axios'

/**
 * Responsible for all HTTP requests.
 */
export default class {
	constructor() {
		axios.defaults.baseURL = '/api'

		// Intercept the request to make sure the token is injected into the header.
		axios.interceptors.request.use(config => {
			config.headers['X-Requested-With'] = 'XMLHttpRequest'
			return config
		})
	}

	request(method, url, data, successCb = null, errorCb = null) {
		axios.request({
			url,
			data,
			method: method.toLowerCase()
		}).then(successCb).catch(errorCb)
	}

	get(url, data, successCb = null, errorCb = null) {
		return this.request('get', url, data, successCb, errorCb)
	}

	post(url, data, successCb = null, errorCb = null) {
		return this.request('post', url, data, successCb, errorCb)
	}

	put(url, data, successCb = null, errorCb = null) {
		return this.request('put', url, data, successCb, errorCb)
	}

	delete(url, data = {}, successCb = null, errorCb = null) {
		return this.request('delete', url, data, successCb, errorCb)
	}
}