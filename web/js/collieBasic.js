YUI.add("collieBasic", function (Y) {
    var obj = {}, attr = {};

    attr.srcNode = {};

    obj.initializer = function () {
        this.initBindEvent();
    };

    obj.initBindEvent = function () {
        var settingChange, deleteBtnClick, caseSearchClick, tmpNode;
        settingChange = Y.bind(this.settingChange, this);
        deleteBtnClick = Y.bind(this.deleteConfirm, this);
        Y.one(".setting-selected-wrap").delegate('change', this.settingChange, 'select');

        if (tmpNode = Y.one(".module-setting-list")) tmpNode.delegate("click", deleteBtnClick, ".btn-delete");
        if (tmpNode = Y.one(".module-case")) tmpNode.delegate("click", deleteBtnClick, ".btn-delete");

        tmpNode = Y.one('.case-wrap input[name=searchText]');
        if (tmpNode) {
            caseSearchClick = Y.bind(this.caseSearch, this);
            tmpNode.on('keydown', caseSearchClick);

            tmpNode = Y.one('.case-wrap .btn-search');
            tmpNode.on('click', caseSearchClick);
        }

    };


    /********case ********/
    obj.caseSearch = function (E) {
        if (E.keyCode != 0 &&  E.keyCode != 13) return ;
        E.halt();
        var caseSearchResponse, formNode;
        formNode = Y.one('.case-wrap .search-form');
        caseSearchResponse = Y.bind(this.caseSearchResponse, this);
        y.loading("", "page");
        y.pjax("", formNode, caseSearchResponse);


    };

    obj.caseSearchResponse = function (res) {
        Y.one('.case-table-wrap').setHTML(res);
    };

    /**setting**********/
    obj.settingChange = function (E) {
        E.halt();
        Y.one('.setting-selected-wrap form').submit();
    };

    obj.deleteConfirm = function (E) {
        var confirmCallback, node, name;
        node = E.currentTarget;
        if (E.ctrlKey && E.ctrlKey == true) {

        } else {
            E.preventDefault();E.stopPropagation();
            name = node.getData('name');
            confirmCallback = Y.bind(this.simulateBtnDelete, this, {event: "click", node: node});
            cancelCallback = "hide";
            y.popDialog("你確定要刪除 " + name + " 嗎?", {ok: confirmCallback, cancel: cancelCallback});
        }
    };

    obj.simulateBtnDelete = function (args) {
        var node, event;
        node = args.node;
        event = args.event;
        node.simulate(event, {"ctrlKey": true});
        this.hide();

    }

    Y.namespace('COLLIE').basic = Y.Base.create('COLLIE_Basic', Y.Base, [],
        obj,
        {
            ATTRS: attr
        }
    );

    obj = null;
    attr = null;

    var s = new Y.COLLIE.basic();

}, '', {requires: ["node", "event", "event-delegate", "node-event-simulate"]});
