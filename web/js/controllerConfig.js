YUI.add("ET_controllerConfig", function (Y) {
    var obj = {}, attr = {};

    
    obj.initializer = function () {
        this.data = {};
        this.controllerProfile= {};
    };

    obj.initBindEvent = function () {

    };

    obj.getConfig = function (key) {
        return this.data[key];
    };

    obj.getControllerProfile = function (key) {
        return this.controllerProfile[key];
    };

    obj.addConfig = function (key, content, profile) {
        this.data[key] = content;
        this.controllerProfile[key] = profile;
    };


    obj.fetchConfig = function (args, controllerParam, callback) {
        var url, data = {}, responseCallback;
        url = 'index.php?page=controllerFetchFormParam';
        data = {
            id: args.id,
            name: args.name,
        };
        if (args.type) data.type = args.type;
        responseCallback = Y.bind(this.fetchComplete, this);
        y.ajax(url, data, responseCallback, {callback: callback, controllerParam: controllerParam, configKey: args.id});
    };

    obj.fetchComplete = function (resObj, form, args) {
        var config;
        config = resObj;
        this.addConfig(args.configKey, config, form);
        
        args.callback(config, args);
    };


    Y.namespace('ET').controllerConfig = Y.Base.create('controllerConfig', Y.Base, [],
        obj,
        {
            ATTRS: attr
        }
    );

    Y.log("[Module controllerConfig] success");
    obj = null;
    attr = null;

}, '', {requires: ["base"]});
