/* global echarts */

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