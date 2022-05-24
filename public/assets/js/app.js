/* global custom_config */

$(document).ready(function () {
    Application = function () {
        this.pool = [];
        this.session = [];
        this.loading = false;
    };
    $.extend(Application.prototype, {
        init: function () {
            this.toastobj = siiimpleToast;
            this.toastobj = this.toastobj.setOptions({
                container: 'body',
                class: 'siiimpleToast',
                position: 'bottom|right',
                margin: 15,
                delay: 0,
                duration: 5000,
                style: {},
            });
        },
        base_url: function ($slug) {
            let url = window.location.protocol + "//" + window.location.hostname + "/" + this.safeVar($slug, "");
            return url;
        },
        toast: function (message, type) {
            switch (type) {
                case "success":
                    this.toastobj.success(message);
                    break;
                case "error":
                    this.toastobj.alert(message);
                    break;
                default:
                    this.toastobj.message(message);
                    break;
            }
        },
        getSelected: function (id, returnText) {
            if (returnText === true) {
                return this.gethtml(`${id} option:selected`);
            } else {
                return this.get(`${id} option:selected`);
            }
        },
        get: function (id) {
            return this.safeVar($(`#${id}`).val(), "");
        },
        set: function (id, value) {
            $(`#${id}`).val(value);
        },
        gethtml: function (id) {
            if (this.idExist(id) === true) {
                return $(`#${id}`).html();
            } else {
                return "";
            }
        },
        sethtml: function (id, value, append) {
            if (this.idExist(id) === true) {
                if (append === true) {
                    $(`#${id}`).append(value);
                } else {
                    $(`#${id}`).html(value);
                }
            }
        },
        get_attr: function (id, attribute) {
            return $(`#${id}`).attr(attribute);
        },
        set_attr: function (id, attribute, value) {
            $(`#${id}`).attr(attribute, value);
        },
        set_session: function (key, value) {
            this.session[key] = value;
        },
        get_session: function (key) {
            if (this.isset(this.session, key)) {
                return this.session[key];
            }
            return null;
        },
        remove_session: function (key) {
            if (this.isset(this.session, key)) {
                return delete this.session[key];
            }
            return false;
        },
        update_session: function (key, value) {
            if (this.isset(this.session, key)) {
                this.session[key] = value;
                return true;
            } else {
                return false;
            }
        },
        addClass: function (objid, class_name, add_class) {
            add_class = (add_class === false) ? false : true;
            if (this.idExist(objid) === true) {
                if (add_class === true) {
                    $(`#${objid}`).addClass(class_name);
                } else {
                    $(`#${objid}`).removeClass(class_name);
                }
            }
        },
        show: function (id, isClass) {
            isClass = this.safeVar(isClass, false);
            let obj = (isClass === true) ? `.${id}` : `#${id}`;
            $(obj).removeClass('hidden');
        },
        hide: function (id, isClass) {
            isClass = this.safeVar(isClass, false);
            let obj = (isClass === true) ? `.${id}` : `#${id}`;
            if (this.idExist(id, isClass)) {
                $(obj).addClass('hidden');
            }
        },
        showModal: function (id) {
            if (this.idExist(id) === true) {
                $('#' + id).modal({ backdrop: 'static', keyboard: false, show: true });
            }
        },
        hideModal: function (id) {
            if (this.idExist(id) === true) {
                $('#' + id).modal('hide');
            }
        },
        idExist: function (id, isClass) {
            let eid = ((isClass === true) ? "." : "#") + id;
            return (this.isUndefined(id) !== true && $(eid).length > 0) ? true : false;
        },
        isset: function (array, key) {
            return (key in array) ? true : false;
            //return (typeof array[key] === 'undefined') ? false : true;
        },
        isUndefined: function (variable) {
            return (typeof variable === 'undefined') ? true : false;
        },
        isArray: function (value) {
            return $.isArray(value);
        },
        isObject: function (value) {
            return (typeof value === 'object');
        },
        isBlank: function (value) {
            return (this.isNull(value) === true || value === "") ? true : false;
        },
        isNull: function (value) {
            return (value === null) ? true : false;
        },
        inArray: function (value, array) {
            if (this.isArray(array) === true) {
                if ($.inArray(value, array) !== -1) {
                    return true;
                }
            }
            return false;
        },
        safeVar: function (variable, defaultValue) {
            try {
                return (this.isUndefined(variable) === false) ? variable : ((this.isUndefined(defaultValue) === false) ? defaultValue : null);
            } catch (err) {
                return defaultValue;
            }
        },
        formData: function (id, extradata) {
            let myForm = (this.idExist(id) === true) ? document.getElementById(id) : document.createElement("form");
            let formdata = new FormData(myForm);
            extradata = this.safeVar(extradata, {});
            if (this.isObject(extradata) === true && this.getLength(extradata, true) > 0) {
                $.each(extradata, function (name, value) {
                    formdata.append(name, value);
                });
            }
            return formdata;
        },
        getLength: function (object, isObject) {
            try {
                if (this.isObject(object) === true) {
                    let length = 0;
                    for (let index in object) {
                        length++;
                    }
                    return length;
                } else if (this.isArray(object) === true) {
                    return object.length;
                } else {
                    if (!this.isBlank(object)) {
                        return object.length;
                    } else {
                        return 0;
                    }
                }
            } catch (err) {
                console.log(err.message, true);
            }
        },
        select_first: function (id) {
            if (this.idExist(id)) {
                $(`#${id}`).val($(`#${id} option:first`).val());
            }
        },
        jsonParse: function (json) {
            try {
                return JSON.parse(json);
            } catch (err) {
                console.error(err.message);
            }
            return {};
        },
        callHttp: function (option, callback) {
            let that = this;
            let defaults = {
                async: true,
                type: 'GET',
                timeout: 180, // In Seconds
                url: "",
                data: '',
                xhrFields: { withCredentials: false },
                processData: true,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8"
            };
            let options = $.extend(defaults, option);
            let decodeJson = this.safeVar(option.jsondecode, false);
            $xhr = $.ajax({
                async: options.async,
                type: options.type,
                url: options.url,
                timeout: (options.timeout * 1000),
                data: options.data,
                xhrFields: options.xhrFields,
                processData: options.processData,
                contentType: options.contentType,
                beforeSend: function (jxhr) {
                    that.pool.push(jxhr);
                },
                success: function (response, status, xhr) {
                    let cont_type = xhr.getResponseHeader("content-type") || "";
                    if (callback !== false) {
                        if (cont_type === "application/json") {
                            callback(((decodeJson === true) ? that.jsonParse(response) : response));
                        } else {
                            that.toast("Unable to handle request. Please try again later");
                            callback([]);
                        }
                    }
                },
                complete: function (jxhr) {
                    let poolIndex = that.pool.indexOf(jxhr);
                    (poolIndex > -1) ? that.pool.splice(poolIndex, 1) : false;
                },
                statusCode: {
                    404: function () {
                        that.toast("Service is not available", "error");
                    },
                    500: function () {
                        that.toast("Unable to handle your request. Please try again later", "error");
                    },
                    504: function () {
                        that.toast("It's taking too much time. Unable to proceed now.");
                        if (callback !== false) {
                            callback([]);
                        }
                    }
                },
                error: function (req, status, xhr) {
                    if (status === 'timeout') {
                        let message = "Unable to handle your request. Please try again later";
                        that.toast(message, "error");
                        console.error(err.message);
                        if (callback !== false) {
                            callback({ status: 'false', logout: 'false', msg: message, data: {} });
                        }
                    }
                }
            });
            return $xhr;
        },
        abortAllHttp: function ($url) {
            let that = this;
            $url = this.safeVar($url);
            try {
                $(that.pool).each(function ($poolIndex, jxhr) {
                    try {
                        if (!$url || $url === jxhr.requestURL) {
                            // Abort the connection and removes this connection from list by index
                            jxhr.abort();
                            that.pool.splice($poolIndex, 1);
                        }
                    } catch (err) {
                        console.error(err.message);
                    }
                });
            } catch (err) {
                console.error(err.message);

            }
        },
        regex: function (test, value) {
            let result = false;
            switch (test) {
                case 'domain':
                    result = /(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]/.test(value);
                    break;
                case 'url':
                    result = /((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)/i.test(value);
                    break;
                case 'email':
                    result = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
                    break;
            }
            return result;
        },
        inputSelect: function (inputid, input_array, name_key, value_key, data_keys, save_first, selected_value) {
            save_first = this.safeVar(save_first, true);
            data_keys = this.isArray(data_keys) ? data_keys : [];
            if (save_first) {
                $('#' + inputid + ' option:not(:first-child)').remove();
            } else {
                $('#' + inputid + ' option').remove();
            }
            if (this.isArray(input_array)) {
                try {
                    let html = "";
                    let that = this;
                    $.each(input_array, function (index, element) {
                        let name = that.safeVar(element[name_key], "");
                        if (name !== "") {
                            let value = that.safeVar(element[value_key], "");
                            let data_attr = "";
                            if (that.isArray(data_keys) === true) {
                                $.each(data_keys, function (index, attr) {
                                    let data_value = $.trim(that.safeVar(element[attr], "Unknown"));
                                    data_attr += `data-${attr}="${data_value}" `;
                                });
                            }
                            html += '<option class="text-capatilize" ' + data_attr + 'value="' + value + '" ' + ((name === selected_value || value === selected_value) ? 'selected="selected"' : '') + '>' + name + '</option>';
                        }
                    });
                    this.sethtml(inputid, html, true);
                } catch (err) {
                    this.debug(err.message, true);
                }
            }
        },
        validate: function (id, type, title, validations, values, callback, isClass) {
            let that = this;
            callback = (this.isUndefined(callback) === true) ? false : callback;
            let isExist = (isClass === true) ? this.idExist(id, true) : this.idExist(id);
            if (isExist === true) {
                validations = this.safeVar(validations, []);
                values = this.safeVar(values, []);
                if (this.isArray(validations) === true) {
                    let message = true;
                    if (this.inArray(type, ["text", "select"])) {
                        let thisData = this.get(id);
                        $.each(validations, function (index, validation) {
                            switch (validation) {
                                case 'blank':
                                    if (thisData === "") {
                                        that.toast(title + " should not be blank", "error");
                                        message = false;
                                        return false;
                                    }
                                    break;
                                case 'length':
                                    let dataLength = that.getLength(thisData);
                                    let validLength = that.safeVar(values[validation], 1);
                                    if (dataLength < validLength) {
                                        that.toast(title + " should be " + validLength + " character long.", "error");
                                        message = false;
                                        return false;
                                    }
                                    break;
                                case 'maxlength':
                                    let thisLength = that.getLength(thisData);
                                    let maxLength = that.safeVar(values[validation], 1);
                                    if (thisLength > maxLength) {
                                        that.toast(`Maximum ${maxLength} ${title} are allowed.`, "error");
                                        message = false;
                                        return false;
                                    }
                                    break;
                                case 'selected':
                                    if (thisData === "" || thisData === null) {
                                        that.toast("Please select " + title + ".", "error");
                                        message = false;
                                        return false;
                                    }
                                    break;
                                case 'maximum':
                                    let maxValue = that.safeVar(values[validation], 0);
                                    if (thisData > maxValue) {
                                        that.toast(title + " should be less than" + maxValue + ".", "error");
                                        message = false;
                                        return false;
                                    }
                                    break;
                                case 'domain':
                                    if (that.regex("domain", thisData) === false) {
                                        that.toast("Please provide valid " + title + ".", "error");
                                        message = false;
                                        return false;
                                    }
                                    break;
                                case 'url':
                                    if (that.regex("url", thisData) === false) {
                                        that.toast("Please provide valid " + title + ".", "error");
                                        message = false;
                                        return false;
                                    }
                                    break;
                                case 'email':
                                    if (that.regex("email", thisData) === false) {
                                        that.toast("Please provide valid " + title + ".", "error");
                                        message = false;
                                        return false;
                                    }
                                    break;
                                case 'unique':
                                    let isUnique = true;
                                    let idList = [];
                                    if (isClass === true && type === "select") {
                                        $("." + id).each(function () {
                                            let value = $app.safeVar($(this).val(), "");
                                            if (value !== "") {
                                                if ($app.inArray(value, idList)) {
                                                    isUnique = false;
                                                    return false;
                                                } else {
                                                    idList.push(value);
                                                }
                                            }
                                        });
                                    }
                                    if (isUnique === false) {
                                        that.toast("Selected all " + title + " should be unique.", "error");
                                        message = false;
                                        return false;
                                    }
                                    break;
                            }
                        });
                    } else if (this.inArray(type, ["image"])) {
                        let file = document.getElementById(id);
                        file = file.files.item(0);
                        let fileName = $app.safeVar(file.name, 'No File Selected');
                        let extension = $app.lowercase(fileName.replace(/^.*\./, ''));
                        if ($app.inArray(extension, ["png", "jpg", "jpeg"]) === true) {
                            let fileType = file.type;
                            let fileSize = file.size;
                            fileSize = Math.round((fileSize / 1024));
                            let reader = new FileReader();
                            reader.onload = function (r) {
                                let img = new Image();
                                img.src = r.target.result;
                                img.onload = function () {
                                    let width = this.width;
                                    let height = this.height;
                                    $.each(validations, function (index, validation) {
                                        switch (validation) {
                                            case 'minwidth':
                                                let validMinWidth = that.safeVar(values[validation], 100);
                                                if (width < validMinWidth) {
                                                    that.toast(title + " width should be more than " + validMinWidth + " Pixel", "error");
                                                    return message = false;
                                                }
                                                break;
                                            case 'minheight':
                                                let validMinHeight = that.safeVar(values[validation], 100);
                                                if (height < validMinHeight) {
                                                    that.toast(title + " height should be more than " + validMinHeight + " Pixel", "error");
                                                    return message = false;
                                                }
                                                break;
                                            case 'maxwidth':
                                                let validMaxWidth = that.safeVar(values[validation], 500);
                                                if (width > validMaxWidth) {
                                                    that.toast(title + " width should be less than " + validMaxWidth + " Pixel", "error");
                                                    return message = false;
                                                }
                                                break;
                                            case 'maxheight':
                                                let validMaxHeight = that.safeVar(values[validation], 500);
                                                if (height > validMaxHeight) {
                                                    that.toast(title + " width should be less than " + validMaxHeight + " Pixel", "error");
                                                    return message = false;
                                                }
                                                break;
                                            case 'maxsize':
                                                let validMaxSize = that.safeVar(values[validation], 1);
                                                if (fileSize > validMaxSize) {
                                                    that.toast(title + " size should be less than " + validMaxSize + "KB", "error");
                                                    return message = false;
                                                }
                                                break;
                                        }
                                    });
                                    if (callback !== false) {
                                        callback(message);
                                    }
                                };
                            };
                            reader.readAsDataURL(file);
                        } else {
                            $app.toast("Select valid image for " + title + ". Example: PNG, JPG, JPEG, ", "warning");
                        }
                    }
                    return message;
                }
            }
            console.error("it seens to be a invalid request. Please try again later.");
            return false;
        }
    });
});