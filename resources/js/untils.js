/*
 * 验证手机号是否合格
 * true--说明合格
 */
export function isPhone(phoneStr) {
    let myreg = /^[1][3,4,5,7,8,9][0-9]{9}$/;
    if (!myreg.test(phoneStr)) {
        return false;
    } else {
        return true;
    }
}

/*
 * 验证身份证号是否合格
 * true--说明合格
 */
export function isIdCard(idCardStr) {
    let idcardReg = /^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/;
    if (idcardReg.test(idCardStr)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 验证车牌号是否合格
 * true--说明合格
 */
export function isVehicleNumber(vehicleNumber) {
    let xreg = /^[京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领A-Z]{1}[A-Z]{1}(([0-9]{5}[DF]$)|([DF][A-HJ-NP-Z0-9][0-9]{4}$))/;
    let creg = /^[京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领A-Z]{1}[A-Z]{1}[A-HJ-NP-Z0-9]{4}[A-HJ-NP-Z0-9挂学警港澳]{1}$/;
    if (vehicleNumber.length == 7) {
        return creg.test(vehicleNumber);
    } else if (vehicleNumber.length == 8) {
        return xreg.test(vehicleNumber);
    } else {
        return false;
    }
}

/*
 * 验证字符串是否为空（也不能为纯空格）
 * true--说明为空， false--说明不为空
 */
export function isEmptyString(string) {
    if (
        string == undefined ||
        typeof string == 'undefined' ||
        !string ||
        string == null ||
        string == '' ||
        /^\s+$/gi.test(string)
    ) {
        return true;
    } else {
        return false;
    }
}

/*
 * 生日转为年龄（精确到月份）
 */
export function birsdayToAge(birsday) {
    let aDate = new Date();
    let thisYear = aDate.getFullYear();
    let bDate = new Date(birsday);
    let brith = bDate.getFullYear();
    let age = thisYear - brith;
    if (aDate.getMonth() == bDate.getMonth()) {
        if (aDate.getDate() < bDate.getDate()) {
            age = age - 1;
        }
    } else {
        if (aDate.getMonth() < bDate.getMonth()) {
            age = age - 1;
        }
    }
    return age;
}

/*
 * 验证是否为数字
 */
export function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

/*
 * 是否为数组
 */
export function isArray(obj) {
    return Object.prototype.toString.call(obj) === '[object Array]';
}

/*
 * 是否空数组
 */
export function isArrayEmpty(val) {
    if (val && val instanceof Array && val.length > 0) {
        return false;
    } else {
        return true;
    }
}

/*
 * 获取url参数字符串
 * 没有返回null
 */
export function getQueryString(name) {
    let reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    let r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}

/*
 * 递归深拷贝
 */
export function deepCopy(obj) {
    let result = Array.isArray(obj) ? [] : {};
    for (let key in obj) {
        if (obj.hasOwnProperty(key)) {
            if (typeof obj[key] === 'object' && obj[key] !== null) {
                result[key] = deepCopy(obj[key]);
            } else {
                result[key] = obj[key];
            }
        }
    }
    return result;
}

/**
 * 去除参数空数据（用于向后台传递参数的时候）
 * @param {Object} obj [参数对象]
 */
export function filterEmptyData(obj) {
    for (let prop in obj) {
        obj[prop] === '' ? delete obj[prop] : obj[prop];
    }
    return obj;
}

/**
 * @desc  函数防抖，用于将多次执行变为最后一次执行
 * @param {function} func - 需要使用函数防抖的被执行的函数。必传
 * @param {Number} wait - 多少毫秒之内触发，只执行第一次，默认1000ms。可以不传
 */
export function debounce(fn, delay) {
    delay = delay || 1000; //默认1s后执行
    let timer = null;
    return function () {
        let context = this;
        let arg = arguments;
        if (timer) {
            clearTimeout(timer);
        }
        timer = setTimeout(() => {
            fn.apply(context, arg);
        }, delay);
    };
}

/**
 * 节流函数, 用于将多次执行变为每隔一段时间执行
 * @param fn 事件触发的操作
 * @param delay 间隔多少毫秒需要触发一次事件
 */
export function throttle2(fn, delay) {
    let timer = null;
    return function () {
        let context = this;
        let args = arguments;
        if (!timer) {
            timer = setTimeout(function () {
                fn.apply(context, args);
                clearTimeout(timer);
            }, delay);
        }
    };
}

/**
 * 时间戳转时间（天分秒）
 * @param msd 时间戳
 */
export function secondToDateStr(msd) {
    let time =msd;
    if (null != time && "" != time) {
        if (time > 60 && time < 60 * 60) {
            time = parseInt(time / 60.0) + "分钟" + parseInt((parseFloat(time / 60.0) -
                parseInt(time / 60.0)) * 60) + "秒";
        } else if (time >= 60 * 60 && time < 60 * 60 * 24) {
            time = parseInt(time / 3600.0) + "小时" + parseInt((parseFloat(time / 3600.0) - parseInt(time / 3600.0)) * 60) + "分钟" + parseInt((parseFloat((parseFloat(time / 3600.0) - parseInt(time / 3600.0)) * 60) - parseInt((parseFloat(time / 3600.0) - parseInt(time / 3600.0)) * 60)) * 60) + "秒";
        } else if (time >= 60 * 60 * 24) {
            time = parseInt(time / 3600.0/24) + "天" +parseInt((parseFloat(time / 3600.0/24)- parseInt(time / 3600.0/24))*24) + "小时" + parseInt((parseFloat(time / 3600.0) - parseInt(time / 3600.0)) * 60) + "分钟" + parseInt((parseFloat((parseFloat(time / 3600.0) - parseInt(time / 3600.0)) * 60) - parseInt((parseFloat(time / 3600.0) - parseInt(time / 3600.0)) * 60)) * 60) + "秒";
        } else {
            time = parseInt(time) + "秒";
        }
    }

    return time;
}