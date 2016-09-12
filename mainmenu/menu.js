YAHOO.util.Event.onContentReady("menubar", function () {
    var oMenuBar = new YAHOO.widget.MenuBar("menubar", {
                                                        autosubmenudisplay: true,
                                                        hidedelay: 750,
                                                        lazyload: true
                                                        });

    oMenuBar.render();
    
});
