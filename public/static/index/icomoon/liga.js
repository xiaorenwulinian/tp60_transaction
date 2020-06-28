/* A polyfill for browsers that don't support ligatures. */
/* The script tag referring to this file must be placed before the ending body tag. */

/* To provide support for elements dynamically added, this script adds
   method 'icomoonLiga' to the window object. You can pass element references to this method.
*/
(function () {
    'use strict';
    function supportsProperty(p) {
        var prefixes = ['Webkit', 'Moz', 'O', 'ms'],
            i,
            div = document.createElement('div'),
            ret = p in div.style;
        if (!ret) {
            p = p.charAt(0).toUpperCase() + p.substr(1);
            for (i = 0; i < prefixes.length; i += 1) {
                ret = prefixes[i] + p in div.style;
                if (ret) {
                    break;
                }
            }
        }
        return ret;
    }
    var icons;
    if (!supportsProperty('fontFeatureSettings')) {
        icons = {
            'house': '&#xe900;',
            'home': '&#xe900;',
            'house2': '&#xe901;',
            'home2': '&#xe901;',
            'house3': '&#xe902;',
            'home3': '&#xe902;',
            'write': '&#xe903;',
            'pencil': '&#xe903;',
            'write2': '&#xe904;',
            'pencil2': '&#xe904;',
            'money5': '&#xe905;',
            'credit-card': '&#xe905;',
            'picture': '&#xe906;',
            'image': '&#xe906;',
            'pictures': '&#xe907;',
            'images': '&#xe907;',
            'pacman': '&#xe90b;',
            'game2': '&#xe90b;',
            'stats3': '&#xe90c;',
            'stats-bars': '&#xe90c;',
            'megaphone': '&#xe90f;',
            'bullhorn': '&#xe90f;',
            'wifi': '&#xe910;',
            'connection': '&#xe910;',
            'read': '&#xe911;',
            'book': '&#xe911;',
            'price-tag': '&#xe91d;',
            'qrcode': '&#xe91f;',
            'purchase': '&#xe920;',
            'cart': '&#xe920;',
            'money4': '&#xe922;',
            'coin-yen': '&#xe922;',
            'compute': '&#xe923;',
            'calculator': '&#xe923;',
            'telephone': '&#xe924;',
            'phone': '&#xe924;',
            'telephone2': '&#xe925;',
            'phone-hang-up': '&#xe925;',
            'mail': '&#xe926;',
            'envelop': '&#xe926;',
            'map-marker': '&#xe927;',
            'location': '&#xe927;',
            'map-marker2': '&#xe928;',
            'location2': '&#xe928;',
            'time2': '&#xe929;',
            'clock': '&#xe929;',
            'time4': '&#xe92a;',
            'alarm': '&#xe92a;',
            'bell': '&#xe92b;',
            'alarm2': '&#xe92b;',
            'mobile': '&#xe92d;',
            'cell-phone': '&#xe92d;',
            'comment': '&#xe93c;',
            'bubble': '&#xe93c;',
            'comments': '&#xe93d;',
            'bubbles': '&#xe93d;',
            'comments2': '&#xe93e;',
            'bubbles2': '&#xe93e;',
            'user': '&#xe942;',
            'profile2': '&#xe942;',
            'users': '&#xe943;',
            'group': '&#xe943;',
            'user5': '&#xe947;',
            'user-tie': '&#xe947;',
            'spinner11': '&#xe954;',
            'loading12': '&#xe954;',
            'search': '&#xe955;',
            'magnifier': '&#xe955;',
            'password': '&#xe95a;',
            'key': '&#xe95a;',
            'secure': '&#xe95c;',
            'lock': '&#xe95c;',
            'gear': '&#xe95e;',
            'cog': '&#xe95e;',
            'stats': '&#xe962;',
            'pie-chart': '&#xe962;',
            'trophy': '&#xe963;',
            'cup': '&#xe963;',
            'present': '&#xe964;',
            'gift': '&#xe964;',
            'flame': '&#xe965;',
            'fire': '&#xe965;',
            'shield': '&#xe967;',
            'security': '&#xe967;',
            'switch': '&#xe968;',
            'todo': '&#xe96a;',
            'list': '&#xe96a;',
            'link': '&#xe971;',
            'chain': '&#xe971;',
            'paperclip': '&#xe972;',
            'attachment': '&#xe972;',
            'views': '&#xe973;',
            'eye': '&#xe973;',
            'star-full': '&#xe979;',
            'rate3': '&#xe979;',
            'like': '&#xe97a;',
            'heart': '&#xe97a;',
            'man': '&#xe97c;',
            'male': '&#xe97c;',
            'woman': '&#xe97d;',
            'female': '&#xe97d;',
            'toilet': '&#xe97e;',
            'man-woman': '&#xe97e;',
            'warning': '&#xe9a7;',
            'sign': '&#xe9a7;',
            'warning2': '&#xe9a8;',
            'notification': '&#xe9a8;',
            'question': '&#xe9a9;',
            'help': '&#xe9a9;',
            'close': '&#xe9ad;',
            'cancel-circle': '&#xe9ad;',
            'forbidden': '&#xe9ae;',
            'blocked': '&#xe9ae;',
            'cross': '&#xe9af;',
            'cancel': '&#xe9af;',
            'tick': '&#xe9b0;',
            'checkmark': '&#xe9b0;',
            'signin': '&#xe9b3;',
            'enter': '&#xe9b3;',
            'signout': '&#xe9b4;',
            'exit': '&#xe9b4;',
            'volume-high': '&#xe9b5;',
            'volume': '&#xe9b5;',
            'repeat2': '&#xe9b9;',
            'loop2': '&#xe9b9;',
            'up3': '&#xe9ba;',
            'circle-up': '&#xe9ba;',
            'right5': '&#xe9bb;',
            'circle-right': '&#xe9bb;',
            'down3': '&#xe9bc;',
            'circle-down': '&#xe9bc;',
            'left5': '&#xe9bd;',
            'circle-left': '&#xe9bd;',
            'resize': '&#xe9c4;',
            'crop': '&#xe9c4;',
            'scissors': '&#xe9c6;',
            'cut': '&#xe9c6;',
            'brand54': '&#xe9e2;',
            'apple': '&#xe9e2;',
            'firefox': '&#xe9f0;',
            'browser2': '&#xe9f0;',
            'browser3': '&#xe9f1;',
            'IE': '&#xe9f1;',
            'opera': '&#xe9f2;',
            'browser4': '&#xe9f2;',
            'pushpin': '&#xe9f3;',
            'pin': '&#xe9f3;',
            'up': '&#xe9f4;',
            'arrow-up': '&#xe9f4;',
            'newspaper': '&#xe9f5;',
            'news': '&#xe9f5;',
            '': '&#xf047;',
            'calendar': '&#xe908;',
            'date': '&#xe908;',
            'display': '&#xe90e;',
            'screen': '&#xe90e;',
            'laptop': '&#xe909;',
            'computer': '&#xe909;',
            'tv': '&#xe912;',
            'television': '&#xe912;',
            'stats-dots': '&#xe90d;',
            'stats2': '&#xe90d;',
            'happy': '&#xe90a;',
            'emoticon': '&#xe90a;',
          '0': 0
        };
        delete icons['0'];
        window.icomoonLiga = function (els) {
            var classes,
                el,
                i,
                innerHTML,
                key;
            els = els || document.getElementsByTagName('*');
            if (!els.length) {
                els = [els];
            }
            for (i = 0; ; i += 1) {
                el = els[i];
                if (!el) {
                    break;
                }
                classes = el.className;
                if (/icon-/.test(classes)) {
                    innerHTML = el.innerHTML;
                    if (innerHTML && innerHTML.length > 1) {
                        for (key in icons) {
                            if (icons.hasOwnProperty(key)) {
                                innerHTML = innerHTML.replace(new RegExp(key, 'g'), icons[key]);
                            }
                        }
                        el.innerHTML = innerHTML;
                    }
                }
            }
        };
        window.icomoonLiga();
    }
}());
