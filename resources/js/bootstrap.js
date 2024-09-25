import _ from 'lodash';
import axios from 'axios';

window._ = _;

try {
    require('bootstrap');
} catch (e) {}

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';