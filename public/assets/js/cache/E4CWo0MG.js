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
});/* global moment */

$(document).ready(function () {
    $.extend(Application.prototype, {
        /** Admin Dashboard Groups Page Script */
        admin_load_category_groups: function (cid) {
            let that = this;
            let next_page = this.get_session("groups_list_next_page");
            let formdata = this.formData("blankform", {
                _ctid: cid,
                _p: (this.isBlank(next_page) ? 0 : next_page)
            });
            if (formdata !== false) {
                let options = { url: this.base_url('rest/groups'), type: "POST", data: formdata, processData: false, contentType: false };
                this.callHttp(options, function (response) {
                    if (response.status === "true") {
                        let childs = response.data.childs;
                        that.set_session("groups_list_next_page", response.data.next);
                        if (that.isArray(childs) && that.getLength(childs) > 0) {
                            that.addClass("groupslist", "blank", false);
                            that.sethtml("groupslist", ``);
                            $.each(childs, function (index, group) {
                                let cssclass = (group.status == 1) ? "catenable" : "catdisable";
                                that.sethtml("groupslist", `<li id="grplist_${group.id}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center pointer ${cssclass}" data-gid="${group.id}">
                                <span class="ellipsis pr-4">${group.name}</span>
                                <img class="img-fluid" src="${group.icon}" />
                            </li>`, true);
                            });
                        }
                    }
                });
            }
        },
        /** Admin Dashboard Category Page Script */
        admin_after_upload_category_banner: function (data) {
            this.sethtml("catbannerurl", data.url);
            this.set_attr("catbannerurl", "href", data.url);
            this.set("catbanner", data.path);
            $('#caticonurlimg').css("background-image", `url(${data.url})`);
        },
        admin_update_category: function (btn) {
            let that = this;
            validate(function (valid) {
                if (valid) {
                    $(btn).find("i.fa").removeClass("fa-floppy-o");
                    $(btn).find("i.fa").addClass("fa-spin fa-circle-o-notch");
                    $(btn).find("span").text("Saving...");
                    let formdata = that.formData("catrupdate");
                    if (formdata !== false) {
                        let options = { url: that.base_url('rest/category/update'), type: "POST", data: formdata, processData: false, contentType: false };
                        that.callHttp(options, function (response) {
                            $(btn).find("i.fa").removeClass("fa-spin fa-circle-o-notch");
                            $(btn).find("i.fa").addClass("fa-floppy-o");
                            $(btn).find("span").text("Save");
                            if (response.status === "true") {
                                that.toast(response.data.message, "success");
                                admin_refresh_category(response.data.category);
                                that.hide("catrupdatesection");
                                that.show("catrdetailsection");
                            } else {
                                that.toast(response.msg, "error");
                            }
                        });
                    }
                }
            });
            function validate(callback) {
                let valid = true;
                callback = (that.isUndefined(callback) === true) ? false : callback;
                valid = (valid === true) ? that.validate("catname", "text", "Name", ["blank", "length", "maxlength"], { length: 5, maxlength: 60 }) : false;
                valid = (valid === true) ? that.validate("catslug", "text", "Slug", ["blank", "length", "maxlength"], { length: 5, maxlength: 60 }) : false;
                if (callback !== false) {
                    callback(valid);
                }
            }
            function admin_refresh_category(parent) {
                // Refresh Category List Data
                if (that.idExist(`ctlist_${parent.id}`)) {
                    $(`#ctlist_${parent.id} span`).text(parent.name);
                    that.set_attr(`ctlist_${parent.id} img`, "src", parent.icon);
                    if (parent.enb == 1 && $(`#ctlist_${parent.id}`).hasClass("catdisable")) {
                        $(`#ctlist_${parent.id}`).removeClass("catdisable");
                        $(`#ctlist_${parent.id}`).addClass("catenable");
                    } else if (parent.enb == 0 && $(`#ctlist_${parent.id}`).hasClass("catenable")) {
                        $(`#ctlist_${parent.id}`).removeClass("catenable");
                        $(`#ctlist_${parent.id}`).addClass("catdisable");
                    }
                }

                // Refresh the category View Data
                that.set_attr("textbannerurl", "href", parent.icon);
                that.set_attr("textcategoryurl", "href", parent.url);
                //that.set_attr("textimgsrc", "src", parent.icon);
                $('#textimgsrc').css("background-image", `url(${parent.icon})`);
                $("#textcatname").text(parent.name);
                $("#textcatslug").text(parent.slug);
                if (parent.pid == 0) {
                    $("#textparentcategory").closest("div").addClass("hidden");
                    $("#textcategoryurl").removeClass("hidden");
                } else {
                    $("#textcategoryurl").addClass("hidden");
                    let parent_category = that.safeVar($(`#catparent option[value='${parent.pid}']`).text(), "");
                    if (!that.isBlank(parent_category)) {
                        $("#textparentcategory").closest("div").removeClass("hidden");
                        $("#textparentcategory").text(parent_category);
                    }
                }
                let viewStates = { 0: "Top", 1: "High", 2: "Middle", 3: "Low", 4: "Lowest", }
                let vst = that.safeVar(viewStates[parent.vst], "");
                if (!that.isBlank(vst)) {
                    $("#textviewstate").closest("div").removeClass("hidden");
                    $("#textviewstate").text(vst);
                } else {
                    $("#textviewstate").closest("div").addClass("hidden");
                }
                if (parent.enb == "1") {
                    $("#textisactive").closest("div").find("i.fa").removeClass("fa-times");
                    $("#textisactive").closest("div").find("i.fa").addClass("fa-check");
                    $("#textisactive").text("Active");
                } else {
                    $("#textisactive").closest("div").find("i.fa").removeClass("fa-check");
                    $("#textisactive").closest("div").find("i.fa").addClass("fa-times");
                    $("#textisactive").text("Inactive");
                }
            }
        },
        admin_load_subcategory: function (cid) {
            let that = this;
            let formdata = this.formData("blankform", {
                _ctid: cid,
            });
            if (formdata !== false) {
                let options = { url: this.base_url('rest/category'), type: "POST", data: formdata, processData: false, contentType: false };
                this.callHttp(options, function (response) {
                    if (response.status === "true") {
                        let childs = response.data.childs;
                        let parent = response.data.parent;
                        if (that.isArray(childs) && that.getLength(childs) > 0) {
                            that.addClass("subctrlist", "blank", false);
                            that.sethtml("subctrlist", ``);
                            $.each(childs, function (index, category) {
                                let cssclass = (category.enb == 1) ? "catenable" : "catdisable";
                                that.sethtml("subctrlist", `<li id="ctlist_${category.id}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center pointer ${cssclass}" data-cid="${category.id}">
                                <span class="ellipsis pr-4">${category.name}</span>
                                <img class="img-fluid" src="${category.icon}" />
                            </li>`, true);
                            });
                        }
                        admin_category_details_view(parent);
                        admin_category_update_form(parent);
                    }
                });
            }

            function admin_category_details_view(parent) {
                console.log(parent);
                that.hide("catrupdatesection");
                that.show("catrdetailsection");
                that.set_attr("textbannerurl", "href", parent.icon);
                that.set_attr("textcategoryurl", "href", parent.url);
                that.set_attr("textgroupsurl", "href", parent.gurl);
                //that.set_attr("textimgsrc", "src", parent.icon);
                $('#textimgsrc').css("background-image", `url(${parent.icon})`);
                $("#textcatname").text(parent.name);
                $("#textcatslug").text(parent.slug);
                $("#textcatgroups").text(parent.groups.toLocaleString('en-IN'));
                if (parent.pid == 0) {
                    $("#textparentcategory").closest("div").addClass("hidden");
                    $("#textcategoryurl").removeClass("hidden");
                } else {
                    $("#textcategoryurl").addClass("hidden");
                    let parent_category = that.safeVar($(`#catparent option[value='${parent.pid}']`).text(), "");
                    if (!that.isBlank(parent_category)) {
                        $("#textparentcategory").closest("div").removeClass("hidden");
                        $("#textparentcategory").text(parent_category);
                    }
                }
                let viewStates = { 0: "Top", 1: "High", 2: "Middle", 3: "Low", 4: "Lowest", }
                let vst = that.safeVar(viewStates[parent.vst], "");
                if (!that.isBlank(vst)) {
                    $("#textviewstate").closest("div").removeClass("hidden");
                    $("#textviewstate").text(vst);
                } else {
                    $("#textviewstate").closest("div").addClass("hidden");
                }
                if (parent.enb == "1") {
                    $("#textisactive").closest("div").find("i.fa").removeClass("fa-times");
                    $("#textisactive").closest("div").find("i.fa").addClass("fa-check");
                    $("#textisactive").text("Active");
                } else {
                    $("#textisactive").closest("div").find("i.fa").removeClass("fa-check");
                    $("#textisactive").closest("div").find("i.fa").addClass("fa-times");
                    $("#textisactive").text("Inactive");
                }
            }

            function admin_category_update_form(parent) {
                that.set("_ctrid", parent.id);
                that.set("catname", parent.name);
                that.set("catslug", parent.slug);
                that.set("catbanner", parent.ricon);
                that.sethtml("catbannerurl", parent.icon);
                that.set_attr("catbannerurl", "href", parent.icon);
                that.set("catparent", parent.pid);
                that.set("catviewstate", parent.vst);
                $('#caticonurlimg').css("background-image", `url(${parent.icon})`);
                $("#catrstatus").prop('checked', (parent.enb == "1"));
            }
        },
        /** Website Public Pages Script */
        home_submitgroup: function () {
            let that = this;
            validate(function (valid) {
                if (valid) {
                    let formdata = that.formData("blankform", {
                        _cid: that.getSelected("share_group_category"),
                        _lnk: that.get("share_invite_link"),
                        _sid: that.getSelected("group_subcategory")
                    });
                    if (formdata !== false) {
                        let options = { url: that.base_url('api/group'), type: "POST", data: formdata, processData: false, contentType: false };
                        that.callHttp(options, function (response) {
                            if (response.status === "true") {
                                that.select_first("share_group_category");
                                that.set("share_invite_link");
                                that.hideModal("group_submit");
                                that.select_first("group_subcategory");
                            }
                        });
                    }
                }
            });
            function validate(callback) {
                let valid = true;
                callback = (that.isUndefined(callback) === true) ? false : callback;
                valid = (valid === true) ? that.validate("share_group_category", "select", "Group Category", ["selected"], {}) : false;
                valid = (valid === true) ? that.validate("share_invite_link", "text", "Group Invite Link", ["blank", "length", "maxlength", "url"], { length: 2, maxlength: 512 }) : false;
                valid = (valid === true) ? that.validate("group_subcategory", "select", "Group Sub Category", ["selected"], {}) : false;
                if (callback !== false) {
                    callback(valid);
                }
            }
        },
        home_sharegroup: function () {
            let that = this;
            let category = this.getSelected("share_group_category", true);
            let category_id = this.getSelected("share_group_category");
            validate(function (valid) {
                if (valid) {
                    let formdata = that.formData("blankform", { _ctid: category_id });
                    if (formdata !== false) {
                        let options = { url: that.base_url('api/subcat'), type: "POST", data: formdata, processData: false, contentType: false };
                        that.callHttp(options, function (response) {
                            if (response.status === "true") {
                                that.sethtml("group_category_name", category);
                                that.inputSelect("group_subcategory", response.data, "name", "id");
                                that.showModal("group_submit");
                            }
                        });
                    }
                }
            });
            function validate(callback) {
                let valid = true;
                callback = (that.isUndefined(callback) === true) ? false : callback;
                valid = (valid === true) ? that.validate("share_group_category", "select", "Group Category", ["selected"], {}) : false;
                valid = (valid === true) ? that.validate("share_invite_link", "text", "Group Invite Link", ["blank", "length", "maxlength", "url"], { length: 2, maxlength: 512 }) : false;
                if (callback !== false) {
                    callback(valid);
                }
            }
        },
        select_category: function ($obj) {
            let that = this;
            let category_id = $obj.val();
            let sub_category_select_id = $obj.data("subcategory");
            let formdata = this.formData("blankform", { _ctid: category_id });
            if (formdata !== false) {
                let options = { url: this.base_url('api/subcat'), type: "POST", data: formdata, processData: false, contentType: false };
                this.callHttp(options, function (response) {
                    if (response.status === "true") {
                        that.inputSelect(sub_category_select_id, response.data, "name", "id");
                    }
                });
            }
        },
        /** Multiplace Uses Script */
        upload_file_to_cloud: function (element) {
            let that = this;
            var formdata = this.formData("blank", { file: $(element).prop("files")[0] });
            if (formdata !== false) {
                let options = {
                    url: this.base_url('rest/cloud'),
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false
                };
                this.callHttp(options, function (response) {
                    if (response.status === "true") {
                        that.toast(response.data.message, "success");
                        try {
                            let callback_method = that.safeVar($(element).data("callback"), "");
                            if (!that.isBlank(callback_method)) {
                                let callMethod = that[callback_method];
                                if (typeof callMethod === 'function') {
                                    callMethod.call(that, response.data);
                                } else {
                                    console.error(`${callback_method} is not a function`);
                                }
                            }
                        } catch (err) {
                            console.error(err.message);
                        }
                    } else {
                        that.toast(response.data, "error");
                    }
                });
            }
        },
    });
});/* global echarts */

$(document).ready(function () {
    let app = new Application();
    app.init();

    /** Uplaod Image */
    $('body').on('change', '.imguploads', function () {
        if ($(this).get(0).files.length > 0) {
            try {
                let callback_method = app.safeVar($(this).data("callback"), "");
                if (!app.isBlank(callback_method)) {
                    let callMethod = app[callback_method];
                    if (typeof callMethod === 'function') {
                        callMethod.call(app, { url: "", path: "" });
                    } else {
                        console.error(`${callback_method} is not a function`);
                    }
                }
                app.upload_file_to_cloud(this);
            } catch (err) {
                console.error(err.message);
            }
        }
    });

    /** Admin Groups Page Actions */
    $('body').on('click', '.ldgrplist li', function () {
        let cid = $(this).data("cid");
        let name = $.trim($(this).text());
        let input_id = $(this).closest("div.collist-container").find("input.form-control").attr("id");
        app.set(input_id, name);
        if (input_id == "search_category") {
            app.set("search_groups", ``);
            app.sethtml("groupslist", ``);
            app.addClass("groupslist", "blank");
        }
        app.admin_load_category_groups(cid);
    });

    /** Admin Category Page Actions */
    $('body').on('click', '#ctr_upd_btn', function () {
        app.admin_update_category(this);
    });

    $('body').on('click', '.ldcatlist li', function () {
        let cid = $(this).data("cid");
        let name = $.trim($(this).text());
        let input_id = $(this).closest("div.collist-container").find("input.form-control").attr("id");
        app.set(input_id, name);
        console.log(input_id);
        if (input_id == "search_category") {
            app.set("search_subctr", ``);
            app.sethtml("subctrlist", ``);
            app.addClass("subctrlist", "blank");
        }
        app.admin_load_subcategory(cid);
    });

    /** Home Page Actions */
    $('body').on('click', '#share_group_btn', function () {
        app.home_sharegroup();
    });

    $('body').on('click', '#submit_group_btn', function () {
        app.home_submitgroup();
    });

    /** Category and Subcategory List */
    $('body').on('click', '#editcatr', function () {
        app.hide("catrdetailsection");
        app.show("catrupdatesection");
    });

    $('body').on('change', '#group_category_target', function () {
        app.select_category($(this));
    });

    /** This will clear all Ajax Request when user will try to reload page **/
    $(window).bind('beforeunload', function () {
        app.abortAllHttp();
    });

    /** This Handler is useful for bound user to enter specific information in input field */
    $('body').on('input keydown keyup mousedown mouseup select contextmenu drop', ".adtext", function (e) {
        $value = $(this).val();
        $format = (typeof $(this).data('format') !== 'undefined') ? $(this).data('format') : "anum";
        $length = (typeof $(this).data('length') !== 'undefined') ? $(this).data('length') : 9999;
        $maximum = (typeof $(this).data('max') !== 'undefined') ? $(this).data('max') : 9999;
        $filter = false;
        switch ($format) {
            case "numr": // Only allowed numbers with fixed length
                $tmp = ($value !== "") ? $value : 0;
                $filter = (/^[0-9]+$/i.test($tmp) && $tmp <= $maximum);
                break;
            case "alph": // only allowed English Characters A - Z or a-z
                $filter = (/^[a-zA-Z]+$/i.test($value) && $value.length <= $length) || $value === "";
                break;
            case "scalp": // Only Allowed small case Characters a-z
                $filter = (/^[a-z]+$/.test($value) && $value.length <= $length) || $value === "";
                break;
            case "anum": // Only allowed alpha numeric value
                $filter = (/^[a-z0-9]+$/i.test($value) && $value.length <= $length) || $value === "";
                break;
            case "anumd": // Only allowed alpha numeric value with dot
                $filter = (/^[A-Za-z0-9.]+$/i.test($value) && $value.length <= $length) || $value === "";
                break;
            case "anspc": // Only allowed alpha numeric value with space
                $filter = (/^[a-z0-9 ]+$/i.test($value) && $value.length <= $length) || $value === "";
                break;
            case "anundr": // Only allowed alpha numeric value with underscore
                $filter = (/^(?!.*__.*)[A-Za-z0-9_]+$/.test($value) && $value.length <= $length) || $value === "";
                break;
            case "anundrdt": // Only allowed alpha numeric value with underscore with Dot
                $filter = (/^(?!.*__.*)[A-Za-z0-9_.]+$/.test($value) && $value.length <= $length) || $value === "";
                break;
            case "ansundr": // Only allowed alpha numeric value with underscore and space
                $filter = (/^(?!.*__.*)(?!.*  .*)[A-Za-z0-9_ ]+$/.test($value) && $value.length <= $length) || $value === "";
                break;
            case "anundh": // Only allowed alpha numeric value with underscore and hyphon
                $filter = (/^(?!.*__.*)(?!.*--.*)[A-Za-z0-9_-]+$/.test($value) && $value.length <= $length) || $value === "";
                break;
            case "anudh": // Only allowed alpha numeric value with underscore, dot and hyphon
                $filter = (/^(?!.*__.*)(?!.*--.*)[A-Za-z0-9_.-]+$/.test($value) && $value.length <= $length) || $value === "";
                break;
            case "anwdh": // Only allowed alpha numeric with dash
                $filter = (/^(?!.*--.*)[A-Za-z0-9-]+$/.test($value) && $value.length <= $length) || $value === "";
                break;
            case "ansdh": // Only allowed alpha numeric with forword slash and dot
                $filter = (/^(?!.*--.*)[A-Za-z0-9./]+$/.test($value) && $value.length <= $length) || $value === "";
                break;
            case "anatdh": // Only allowed alpha numeric with forword slash and dot
                $filter = (/^(?!.*--.*)[A-Za-z0-9.@]+$/.test($value) && $value.length <= $length) || $value === "";
                break;
            default: // Allowed any character but length will be restricted
                $filter = $value.length <= $length;
                break;
        }
        if ($filter) {
            $(this).data("old", $value);
            return true;
        } else {
            let oldvalue = $app.safeVar($(this).data("old"), "");
            $(this).val(oldvalue);
            return false;
        }
    });

}); // Close Jquery