/* global moment */

$(document).ready(function () {
    $.extend(Application.prototype, {
        /** Admin Dashboard Script */
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
});