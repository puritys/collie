YUI.add("advanceUI", function (Y) {
    var obj = {}, attr = {};

    attr.srcNode = {};

    obj.initializer = function () {//{{{
        var render, srcNode, controllerText, controllerData, key, controllerProfile;
        var i, n, controllerLen;
        srcNode = this.get('srcNode');
        this.controllerIndex = 1;
        this.descObj = {"cr": []};
        this.main = srcNode.one('.main-wrap');
        this.config = new Y.ET.controllerConfig();
        this.controllerObjNode = srcNode.one('.controller-object');
        this.initBindEvent();
        controllerText = srcNode.one('textarea[name=descriptor]').get('value');

        if (controllerText) {
//            try {
                configText = Y.JSON.parse(srcNode.one('.config-text').getHTML());
                controllerData = Y.JSON.parse(controllerText);

                n = configText.length; 
                controllerLen = controllerData['scenario'].length;
                for (key in configText) {
                    config = configText[key];
                    for (j = 0; j < controllerLen; j++) {
                        if (key == controllerData['scenario'][j]['name']) {
                            controllerProfile = controllerData['scenario'][j];
                          //  controllerProfile['name'] = key;
                        }
                    }
                    this.config.addConfig(key, config, controllerProfile);
                }
  /*          } catch (e) {
                Y.log(e, "error");
                alert("Descriptor or config format is wrong.");
                return ;
            }*/

            this.initController(controllerData);
        }
        this.categoryInit();

    };//}}}

    obj.initBindEvent = function () {//{{{
        var srcNode, controllerWrap, clickAdd, clickSubmit, clickEditController, clickDeleteController;
        var controllDrag;
        srcNode = this.get('srcNode');
        controllerWrap = srcNode.one('.controller-wrap');
        clickAdd = Y.bind(this.clickAddButton, this);
        clickSubmit = Y.bind(this.clickSubmit, this);
        clickEditController = Y.bind(this.clickEditController, this);
        clickDeleteController = Y.bind(this.clickDeleteController, this);

        controllerWrap.delegate('click', clickAdd, 'li');
        srcNode.delegate('click', clickSubmit, '.btn-submit');
        controllDrag = Y.bind(this.drag, this);

        srcNode.delegate('mousedown', controllDrag, '.title-bar .name');
        srcNode.delegate('click', clickEditController, '.btn-edit');
        srcNode.delegate('click', clickDeleteController, '.btn-delete');

    };//}}}

    obj.addController = function (args) {//{{{
        var file, id, name, descObj, c, appendCallback, param = {};

        appendCallback = Y.bind(this.append, this);
        y.loading('', 'page'); 
        this.config.fetchConfig(args, args.param, appendCallback);
    };//}}}


    /**
    * To drag controller and change the sort of controller
    */
    obj.drag = function (E) {//{{{
        var dropCallback, srcNode;
        var node, controllerNode;
        if (E.button !=1 ) { return ;}

        E.halt();
        srcNode = this.get('srcNode');
        dropCallback = Y.bind(this.drop, this);
        node = E.currentTarget;
        controllerNode = this.findControllerNode(node);
        srcNode.all('.controller-object .content').addClass('hidden');
        y.dragTo(controllerNode, '.desc-controller', {drop: dropCallback});
    };//}}}

    obj.drop = function (E) {
Y.log("drop");
return true;
    };

    obj.deleteController = function (node) {
        node.remove();
        this.dialog.hide();        
    };

    obj.submit = function () {
        var srcNode;
        srcNode = this.get('srcNode');
        srcNode.one('.case-form').submit();
    };

    obj.getAllSetting = function () {//{{{
        var controllerNodes, srcNode, data = [], cr, i, n, config, j, input, controllerNode, param, scenario, allScen = {}, inputType, configKey, config, controllerProfile;

        srcNode = this.get('srcNode');
        controllerNodes = srcNode.all('.controller-object .desc-controller');
        n = controllerNodes.size();
        for (i = 0; i < n; i++) {
            //config = this.descObj['cr'][i]['config'];
            controllerNode = controllerNodes.item(i);
            configKey = controllerNode.getData('config-key');
            config = this.config.getConfig(configKey);
            controllerProfile = this.config.getControllerProfile(configKey);
            param = {};

            scenario = {
                name: controllerProfile['name']
            };

            if (controllerProfile['type'] && controllerProfile['type'] == "test") {
                //scenario['test'] = controllerProfile['file'];
                //delete scenario['controller'];
                scenario['type'] = "test";
            }
            /*for (pro in config) {
                if (pro == "data") continue;
                input = controllerNode.one('.param-' + pro + '');
                value = input.get('value');
                inputType = input.getAttribute('type');
                if ("checkbox" == inputType) {
                    if (!input.get('checked')) {
                        value = "";
                    }
                }
                param[pro] = value;
            }*/
            //scenario['params'] = param;
            data.push(scenario);
        }
        allScen['scenario'] = data;
        allScen = Y.JSON.stringify(allScen);
        node = srcNode.one('textarea[name=descriptor]');
        node.set('value', allScen);
    };//}}}

    /**
    * when user edit this case, we initialize the controller to let user to edit.
    */
    obj.initController = function (descr) {//{{{
        var scenario, len, scenarioIndex, it, config;
        scenario = descr['scenario'];
        len = scenario.length;
        for (scenarioIndex = 0; scenarioIndex < len; scenarioIndex++) {
            it = scenario[scenarioIndex];
            if (!it['type']) it['type'] = "controller";
            config = this.config.getConfig(it['name']);
            this.append(config,
                {controllerParam: it['params'], configKey: it['name']}
            );
            /*this.addController({
                file:  it['controller'].replace(/^form\//, ''),
                id:    it['id'],
                name:  it['name'],
                type:  it['type'],
                param: it['params']
            }, config);*/

        }
    };//}}}

    /*##########################
    ## render                 ##    
    ###########################*/
    obj.append = function (config, args) {//{{{
        var html, config, newController = {}, controllerParam, configKey;
        controllerParam = args.controllerParam;
        configKey = args.configKey;
        html = this.getNewNode(config, controllerParam, configKey);
        this.controllerObjNode.append(html);
    };//}}}

    obj.getNewNode = function (config, param, configKey) {//{{{
        var it, o ,select, row, value, valueNode, valueNodeWrap, controllerProfile, fieldName, baseFieldName, indexNode;
        var wrap = document.createElement('div');
        var name = document.createElement('div');
        var titleWrap = document.createElement('div');
        var contentWrap = document.createElement('div');
        controllerProfile = this.config.getControllerProfile(configKey)
        contentWrap.className = "form-horizontal content hidden";
        wrap.className = "desc-controller";
        wrap.setAttribute('data-config-key', configKey);
        name.className = "name yui3-u";
        name.innerHTML = controllerProfile.name + ' &nbsp;<span class="glyphicon glyphicon-move"></span>';

        titleWrap.appendChild(name);
        titleWrap.className = "title-bar yui3-g";

        var btnWrap = document.createElement('div');
        btnWrap.className = "fl-right-end yui3-u";
        var btnEdit = document.createElement('button');
        var btnDelete = document.createElement('button');
        btnEdit.className = "btn btn-default btn-edit";
        btnDelete.className = "btn btn-default btn-delete";
        btnDelete.setAttribute('data-name', controllerProfile.name);
        btnEdit.innerHTML = '<span href="#" class="glyphicon glyphicon-edit">Edit</span>';
        btnDelete.innerHTML = '<span href="#" class="glyphicon glyphicon-remove">Delete</span>';

        btnWrap.appendChild(btnEdit);
        btnWrap.appendChild(btnDelete);

        titleWrap.appendChild(btnWrap);
        wrap.appendChild(titleWrap);

        var baseFieldName = configKey + "_" + this.controllerIndex + "_";
        indexNode = document.createElement('input');
        indexNode.name = "index[]";
        indexNode.value = this.controllerIndex;
        indexNode.type = "hidden";
        contentWrap.appendChild(indexNode);
        this.controllerIndex++;

        for (pro in config) {
            it = config[pro];
            if (!it || !it.label) {continue;}
            row = document.createElement('div');
            label = document.createElement('label');
            row.className = "form-group";
            label.className = "yui3-u-1-3 control-label";
            label.innerHTML = it.label;
            valueNodeWrap = document.createElement('div');
            valueNodeWrap.className = "yui3-u-2-3";
            valueNode = document.createElement('div');
            valueNode.className = "value-node";
            valueNodeWrap.appendChild(valueNode);


            row.appendChild(label);
            value = "";
            if (param && param[pro]) {
                value = param[pro];
            }

            fieldName = baseFieldName +  pro;
            switch (it.type) {
                case 'select':
                    select = document.createElement('select');
                    select.className = "form-control param-" + pro;
                    select.name = fieldName;
                    optN = it.options.length;
                    for (i = 0; i< optN; i++) {
                        o = document.createElement('option');
                        if (Y.Lang.isArray(it.options[i])) {
                            o.innerHTML = it.options[i][1];
                            o.value = it.options[i][0];
                            if (value == o.value) {
                                o.selected = true;
                            }                          
                        } else {
                            o.innerHTML = it.options[i];
                            o.value = it.options[i];
                            if (value == o.value) {
                                o.selected = true;
                            }
                        }
                        select.appendChild(o);
                    }
                    valueNode.appendChild(select);
                break;
                case 'input':
                    input = document.createElement('input');
                    input.className = "form-control param-" + pro;
                    input.name = fieldName;
                    input.value = value;
                    valueNode.appendChild(input);
                break;
                case 'checkbox':
                    input = document.createElement('input');
                    input.type = "checkbox";
                    input.className = "param-" + pro;
                    input.name = fieldName;
                    input.value = true;
                    if (value === true || value === "true") input.checked = true;
                    valueNode.appendChild(input);
                break;


            }
            if (it.hint) {
                var hint = document.createElement('div');
                hint.innerHTML = "<span class='glyphicon glyphicon-info-sign'></span> " + it.hint;
                hint.className = "help-block"
                valueNode.appendChild(hint);
            }


            row.appendChild(valueNodeWrap);
            contentWrap.appendChild(row);
        }
        wrap.appendChild(contentWrap);
        return wrap;

    };//}}}

    obj.findControllerNode = function (node) {
        var parent = node, i = 0;
        while (!parent.hasClass('desc-controller')) {
            parent = parent.ancestor();
            i++;
            if (i > 10) {return ;}
        }
        return parent;
    };

    /*##########################
    ## event handle           ##    
    ###########################*/
    obj.clickAddButton = function (E) {//{{{
        var node, file, id, name, type;
        E.halt();
        node = E.currentTarget;
        node = node.one('a');
        file = node.getData('file');
        id = node.getData('id');
        name = node.getData('name');
        type = node.getData('type');
        this.addController({
            file: file,
            id: id,
            name: name,
            type: type
        });
    };//}}}

    obj.clickSubmit = function (E) {
        E.halt();
        this.getAllSetting();
        this.submit();
    };

    obj.clickEditController = function (E) {
        E.halt();
        var node, controllerNode, contentNode;
        node = E.currentTarget;
        controllerNode = this.findControllerNode(node);
        contentNode = controllerNode.one('.content');
        if (contentNode.hasClass('hidden')) {
            contentNode.removeClass('hidden');
        } else {
            contentNode.addClass('hidden');
        }
    };

    obj.clickDeleteController = function (E) {
        E.stopPropagation();
        E.preventDefault();
        var node, controllerNode, contentNode, callback = {};
        node = E.currentTarget;
        controllerNode = this.findControllerNode(node);
        callback.ok = Y.bind(this.deleteController, this, controllerNode);
        callback.cancel = "hide";

        this.dialog = y.popDialog("你是否要移除 " + node.getData('name') +" ?", callback);
    };

    obj.initRender = function (data) {
        Y.log(data);
    };


    /********************
       Category select
    ********************/
    obj.categoryInit = function () {
        var srcNode, multipleSelectWrap, selectNode;

    };

    Y.namespace('ET').advanceUI = Y.Base.create('advanceUI', Y.Base, [],
        obj,
        {
            ATTRS: attr
        }
    );

    obj = null;
    attr = null;

    if (Y.one('.mod-advance')) {
        new Y.ET.advanceUI({srcNode: Y.one('.mod-advance')});
    }

}, '', {requires: ["ET_controllerConfig", "node-event-delegate", "dialog", "json-stringify"]});
