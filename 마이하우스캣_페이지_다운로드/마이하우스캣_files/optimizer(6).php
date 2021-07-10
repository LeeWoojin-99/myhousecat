/**
 * 비동기식 데이터 - 비동기장바구니 레이어
 */
CAPP_ASYNC_METHODS.aDatasetList.push('MobileMutiPopup');
CAPP_ASYNC_METHODS.MobileMutiPopup = {
    __$target: EC$('div[class^="ec-async-multi-popup-layer-container"]'),

    isUse: function()
    {
        if (this.__$target.length > 0) {
            return true;
        }
        return false;
    },

    execute: function()
    {
        for (var i=0; i < this.__$target.length; i++) {
            EC$.ajax({
                url: '/exec/front/popup/AjaxMultiPopup?index='+i,
                data: EC_ASYNC_MULTI_POPUP_OPTION[i],
                dataType: "json",
                success: function (oResult) {
                    switch (oResult.code) {
                        case '0000' :
                            if (oResult.data.length < 1) {
                                break;
                            }
                            EC$('.ec-async-multi-popup-layer-container-' + oResult.data.html_index).html(oResult.data.html_text);
                            if (oResult.data.type == 'P') {
                                BANNER_POPUP_OPEN.setPopupSetting();
                                BANNER_POPUP_OPEN.setPopupWidth();
                                BANNER_POPUP_OPEN.setPopupClose();
                            } else {
                                /**
                                 * 이중 스크롤 방지 클래스 추가(비동기) 
                                 *
                                 */
                                EC$('body').addClass('eMobilePopup');
                                EC$('body').width('100%');

                                BANNER_POPUP_OPEN.setFullPopupSetting();
                                BANNER_POPUP_OPEN.setFullPopupClose();
                            }
                            break;
                        default :
                            break;
                    }
                },
                error: function () {
                }
            });
        }
    }
};
/**
 * 비동기식 데이터 - 좋아요 상품 갯수
 */
CAPP_ASYNC_METHODS.aDatasetList.push('MyLikeProductCount');
CAPP_ASYNC_METHODS.MyLikeProductCount = {
    __iMyLikeCount: null,

    __$target: EC$('#xans_myshop_like_prd_cnt'),
    __$target_main: EC$('#xans_myshop_main_like_prd_cnt'),
    isUse: function()
    {
        if (this.__$target.length > 0 && SHOP.getLanguage() === 'ko_KR') {
            return true;
        }

        if (this.__$target_main.length > 0 && SHOP.getLanguage() === 'ko_KR') {
            return true;
        }

        return false;
    },
    restoreCache: function()
    {
        var sCookieName = 'like_product_cnt' + EC_SDE_SHOP_NUM;
        var re = new RegExp('(?:^| |;)' + sCookieName + '=([^;]+)');
        var aCookieValue = document.cookie.match(re);
        if (aCookieValue) {
            this.__iMyLikeCount = parseInt(aCookieValue[1], 10);
            return true;
        }

        return false;
    },

    setData: function(sData)
    {
        this.__iMyLikeCount = Number(sData);
    },

    execute: function()
    {
        if (SHOP.getLanguage() === 'ko_KR') {
            this.__$target.html(this.__iMyLikeCount + '개');
            this.__$target_main.html(this.__iMyLikeCount);
        }
    }
};
/**
 * 비동기식 데이터 - 좋아요 상품 list
 */
CAPP_ASYNC_METHODS.aDatasetList.push('MyLikeProductList');
CAPP_ASYNC_METHODS.MyLikeProductList = {
    __aMyLikeList: null,
    __iMyLikeListLimit: 10,
    __$target: EC$('.xans-product-likeproductasync'),
    isUse: function()
    {
        if (this.__$target.length > 0 && SHOP.getLanguage() === 'ko_KR') {
            return true;
        }

        if (EC$('#EC_LIKE_ASYNC_LINK_DATA_LIST').length > 0) {
            return true;
        }
        return false;
    },
    setData: function(aData)
    {
        this.__iMyLikeListLimit = EC_FRONT_JS_CONFIG_SHOP.aSyncLikeLimit;
        this.__aMyLikeList = aData;
    },
    execute: function()
    {

        if (this.__aMyLikeList === null || this.__aMyLikeList.length === 0) {
            EC$('#EC_LIKE_ASYNC_LINK_DATA_EMPTY').html('');
            return;
        }

        //EC$('#EC_LIKE_ASYNC_LINK_DATA_EMPTY').remove();
        var sSpaceIcon = ' ';
        for (var iKey = 0; iKey < this.__aMyLikeList.length; iKey++) {
            var oRowData = EC$('#EC_LIKE_ASYNC_LINK_DATA_LIST_TEMP').clone().removeAttr('id');
            oRowData.find('a[href^="/product/detail.html"').attr('href', this.__aMyLikeList[iKey].link_product_detail);
            oRowData.find('.thumb img').attr('src',this.__aMyLikeList[iKey].image_medium);
            oRowData.find('.EC_LIKE_ASYNC_LINK_DATA_PRODUCT_NAME').html('<a href="' + this.__aMyLikeList[iKey].link_product_detail + '">' + this.__aMyLikeList[iKey].disp_product_name + '</a>');

            var sIconListHtml = this.__aMyLikeList[iKey].soldout_icon + sSpaceIcon + this.__aMyLikeList[iKey].stock_icon + sSpaceIcon + this.__aMyLikeList[iKey].recommend_icon + sSpaceIcon +
                this.__aMyLikeList[iKey].new_icon + sSpaceIcon + this.__aMyLikeList[iKey].product_icons + sSpaceIcon + this.__aMyLikeList[iKey].benefit_icons;
             if (sIconListHtml !== '') {
                oRowData.find('.EC_LIKE_ASYNC_LINK_DATA_ICON_LIST').html(sIconListHtml);
            }

            EC$('#EC_LIKE_ASYNC_LINK_DATA_APPEND').append(oRowData);

            if (iKey >= (this.__iMyLikeListLimit - 1)) {
                break;
            }
        }
        EC$('#EC_LIKE_ASYNC_LINK_DATA_LIST_TEMP').remove();
        if (this.__aMyLikeList.length < this.__iMyLikeListLimit) {
            EC$('#EC_LIKE_ASYNC_LINK_DATA_MORE_VIEW').remove();
        }

        if (EC_FRONT_JS_CONFIG_SHOP.bAutoView === 'T') {
            document.getElementById('EC_LIKE_ASYNC_LINK_DATA_LIST').style.display = 'block';
        }

    }
};
/**
 * 라이브 링콘 on/off이미지
 */
CAPP_ASYNC_METHODS.aDatasetList.push('Livelinkon');
CAPP_ASYNC_METHODS.Livelinkon = {
    __$target: EC$('#ec_livelinkon_campain_on'),
    __$target2: EC$('#ec_livelinkon_campain_off'),

    isUse: function()
    {
        if (this.__$target.length > 0 && this.__$target2.length > 0) {
            return true;
        }
        return false;
    },

    execute: function()
    {
        var sCampaignid = '';
        if (EC_ASYNC_LIVELINKON_ID != undefined) {
            sCampaignid = EC_ASYNC_LIVELINKON_ID;
        }
        EC$.ajax({
            url: '/exec/front/Livelinkon/Campaignajax?campaign_id='+sCampaignid,
            async: false,
            success: function(data) {
                if (data == 'on') {
                    CAPP_ASYNC_METHODS.Livelinkon.__$target.removeClass('displaynone').show();
                    CAPP_ASYNC_METHODS.Livelinkon.__$target2.removeClass('displaynone').hide();
                } else if (data == 'off') {
                    CAPP_ASYNC_METHODS.Livelinkon.__$target.removeClass('displaynone').hide();
                    CAPP_ASYNC_METHODS.Livelinkon.__$target2.removeClass('displaynone').show();
                } else {
                    CAPP_ASYNC_METHODS.Livelinkon.__$target.removeClass('displaynone').hide();
                    CAPP_ASYNC_METHODS.Livelinkon.__$target2.removeClass('displaynone').hide();
                }
            }
        });
    }
};
/**
 * 비동기식 데이터 - 마이쇼핑 > 주문 카운트 (주문 건수 / CS건수 / 예전주문)
 */
CAPP_ASYNC_METHODS.aDatasetList.push('OrderHistoryCount');
CAPP_ASYNC_METHODS.OrderHistoryCount = {
    __sTotalOrder: null,
    __sTotalOrderCs: null,
    __sTotalOrderOld: null,

    __$target: EC$('#ec_myshop_total_orders'),
    __$target2: EC$('#ec_myshop_total_orders_cs'),
    __$target3: EC$('#ec_myshop_total_orders_old'),

    isUse: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            if (this.__$target.length > 0) {
                return true;
            }

            if (this.__$target2.length > 0) {
                return true;
            }

            if (this.__$target3.length > 0) {
                return true;
            }
        }

        return false;
    },

    setData: function(aData)
    {
        this.__sTotalOrder = aData['total_orders'];
        this.__sTotalOrderCs = aData['total_orders_cs'];
        this.__sTotalOrderOld = aData['total_orders_old'];

    },

    execute: function()
    {
        this.__$target.html(this.__sTotalOrder);
        this.__$target2.html(this.__sTotalOrderCs);
        this.__$target3.html(this.__sTotalOrderOld);
    }
};
/**
 * 주문조회 > 주문내역조회 및 취소/교환/반품내역 등 탭(OrderHistoryTab) 갯수 비동기호출
 */
CAPP_ASYNC_METHODS.aDatasetList.push('OrderHistoryTab');
CAPP_ASYNC_METHODS.OrderHistoryTab = {
    __$targetTotalOrders: EC$('#xans_myshop_total_orders'),
    __$targetTotalOrdersCs: EC$('#xans_myshop_total_orders_cs'),
    __$targetTotalOrdersPast: EC$('#xans_myshop_total_orders_past'),
    __$targetTotalOrdersOld: EC$('#xans_myshop_total_orders_old'),

    isUse: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            if (EC$('.xans-myshop-orderhistorytab').length > 0) {
                return true;
            }
        }
        return false;
    },
    execute: function()
    {
        try {
            var mode = this.getUrlParam('mode');
            var order_id = this.getUrlParam('order_id');
            var order_status = this.getUrlParam('order_status');
            var history_start_date = this.getUrlParam('history_start_date');
            var history_end_date = this.getUrlParam('history_end_date');
            var past_year = this.getUrlParam('past_year');
            var count = this.getUrlParam('count');

            var sPathName = window.location.pathname;

            var oParameters = {
                'mode': mode == null ? '' : mode,
                'order_id': order_id == null ? '' : order_id,
                'order_status': order_status == null ? '' : order_status,
                'history_start_date': history_start_date == null ? '' : history_start_date,
                'history_end_date': history_end_date == null ? '' : history_end_date,
                'past_year': past_year == null ? '' : past_year,
                'count': count == null ? '' : count,
                'page_name': sPathName.substring(sPathName.lastIndexOf("/") + 1, sPathName.indexOf('.'))
            };

            if (typeof EC_ASYNC_ORDERHISTORYTAB_ORDER_ID !== 'undefined') {
                oParameters['encrypted_str'] = EC_ASYNC_ORDERHISTORYTAB_ORDER_ID;
            }

            var oThis = this;

            EC$.ajax({
                url: '/exec/front/Myshop/OrderHistoryTab',
                dataType: 'json',
                data: oParameters,
                success: function (aData) {
                    if (aData['result'] === true) {
                        oThis.__$targetTotalOrders.html(aData['total_orders']);
                        oThis.__$targetTotalOrdersCs.html(aData['total_orders_cs']);
                        oThis.__$targetTotalOrdersOld.html(aData['total_orders_old']);
                        oThis.__$targetTotalOrdersPast.html(aData['total_orders_past']);

                        var oTabATagList = {
                            'param': EC$('.tab_class a'),
                            'param_cs': EC$('.tab_class_cs a'),
                            'param_past': EC$('.tab_class_past a'),
                            'param_old': EC$('.tab_class_old a'),
                        };
                        var sHref;
                        EC$.each(oTabATagList, function(sKey, oTarget) {
                            if (oTarget.length > 0) {
                                sHref = oTarget.attr("href");
                                sHref = sHref.replace("$" + sKey, aData[sKey]);
                                oTarget.attr("href", sHref);
                            }
                        });

                        EC$("." + aData['selected_tab_class']).addClass('selected');

                        if (aData['is_past_list_display'] === false) {
                            EC$('.tab_class_past').addClass("displaynone");
                        } else {
                            EC$('.tab_class_past').removeClass("displaynone");
                        }

                        if (aData['old_list_display'] === false) {
                            EC$('.tab_class_old').addClass("displaynone");
                        } else {
                            EC$('.tab_class_old').removeClass("displaynone");
                        }
                    }
                }
            });
        } catch (oError) {
            this.errorAjaxCall(oError);
        }
    },
    getUrlParam: function(name)
    {
        var param = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (param == null) {
            return null;
        } else {
            return decodeURI(param[1]) || null;
        }
    },
    errorAjaxCall: function(oError)
    {
        var sError = oError.toString();
        var aMatch = sError.match(/Error*/g);

        if (typeof(oError) !== 'object' || aMatch == null || aMatch.length < 1 || !oError.stack) return;

        EC$.ajax({
            url: '/exec/front/order/FormJserror/',
            method: 'POST',
            cache: false,
            async: false,
            data: {
                errorMessage: oError.message,
                errorStack: oError.stack,
                errorName: oError.name
            }
        });
    }
};
var PathRoleValidator = (function() {
    /**
     * Milage, Deposit 의 경우 처리되지 말아야할 페이지 확인
     * @returns {boolean}
     */
    function isInvalidPathRole()
    {
        // path role
        var sCurrentPathRole = null;

        // // euckr 환경에서 path role 획득
        if (SHOP.getProductVer() === 1) {
            // path 와 role 매핑
            var aPathRoleMap = {
                '/myshop/index.html': 'MYSHOP_MAIN',
                '/myshop/mileage/historyList.html': 'MYSHOP_MILEAGE_LIST',
                '/myshop/deposits/historyList.html': 'MYSHOP_DEPOSIT_LIST',
                '/order/orderform.html': 'ORDER_ORDERFORM'
            };

            // 페이지 경로로부터 path role 획득
            sCurrentPathRole = aPathRoleMap[document.location.pathname];

            // utf8 환경에서 path role 획득
        } else {
            // 현재 페이지 path role 획득
            sCurrentPathRole = EC$('meta[name="path_role"]').attr('content');
        }

        // 처리되면 안되는 경로
        var aInvalidPathRole = [
            'MYSHOP_MAIN',
            'MYSHOP_MILEAGE_LIST',
            'MYSHOP_DEPOSIT_LIST',
            'ORDER_ORDERFORM'
        ];

        return EC$.inArray(sCurrentPathRole, aInvalidPathRole) >= 0;
    }

    return {
        isInvalidPathRole: isInvalidPathRole
    };
})();
EC$(function()
{
    CAPP_ASYNC_METHODS.init();
});
var EC_MANAGE_PRODUCT_RECENT = {
    getRecentImageUrl: function()
    {
        var sStorageKey = 'localRecentProduct' + EC_SDE_SHOP_NUM;

        if (typeof(sessionStorage[sStorageKey]) !== 'undefined') {
            var sRecentData = sessionStorage.getItem(sStorageKey);
            var oJsonData = JSON.parse(sRecentData);
            var sImageSrc = '';

            if (oJsonData[0] !== undefined) {
                sImageSrc = oJsonData[0].sImgSrc;
            }
            
            document.location.replace('recentproduct://setinfo?simg_src=' + sImageSrc);
        }
    }
};

var EC_MANAGE_MEMBER = {
    // 카카오싱크 로그인
    kakaosyncLogin: function (clientSecret)
    {
        if (Kakao.isInitialized()) {
            Kakao.cleanup();
        }
        Kakao.init(clientSecret);

        Kakao.Auth.authorize({
            redirectUri: location.origin + '/Api/Member/Oauth2ClientCallback/kakao/'
        });
    }
};
var EC_EXTERNAL_FRONT_APPSCRIPT = {
    insertAppScript: function() {
        if (typeof EC_APPSCRIPT_ASSIGN_DATA !== "undefined" && Array.isArray(window.EC_APPSCRIPT_ASSIGN_DATA)) {
            while (EC_APPSCRIPT_ASSIGN_DATA.length > 0) {
                var oSrcData = EC_APPSCRIPT_ASSIGN_DATA.pop();
                EC_EXTERNAL_FRONT_APPSCRIPT.appendAppScript(oSrcData['src'], oSrcData['integrity']);
            }
        }
        if (typeof EC_APPSCRIPT_SOURCE_DATA !== "undefined" && Array.isArray(window.EC_APPSCRIPT_SOURCE_DATA)) {
            while (EC_APPSCRIPT_SOURCE_DATA.length > 0) {
                EC_EXTERNAL_FRONT_APPSCRIPT.appendSourceTypeScript(EC_APPSCRIPT_SOURCE_DATA.pop());
            }
        }
    },
    appendAppScript: function(sSrc, sIntegrity) {
        var js = document.createElement('script');
        js.src = sSrc;
        // integrity 필드가 존재하는 경우 스크립트 무결성 체킹을 위해 추가.
        if (sIntegrity && sIntegrity !== null) {
            js.integrity = sIntegrity;
            js.crossOrigin = "anonymous";
        }
        document.body.appendChild(js);
    },
    appendSourceTypeScript: function (sSrc) {
        var js = document.createElement('script');
        js.type = 'text/javascript';
        js.text = EC_EXTERNAL_FRONT_APPSCRIPT.base64Decode(sSrc);
        document.body.appendChild(js);
    },
    base64Decode: function (sEncoded) {
        return decodeURIComponent(atob(sEncoded).split('').map(function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
    }
};
if (window.addEventListener) {
    window.addEventListener('load', EC_EXTERNAL_FRONT_APPSCRIPT.insertAppScript);
} else if (window.attachEvent) {
    window.attachEvent('onload', EC_EXTERNAL_FRONT_APPSCRIPT.insertAppScript);
}
/**
 * SDK spec interface
 */
EC_EXTERNAL_UTIL_APP_SPECINTERFACE = {

    oMemberInfo: {
        member_id: null,
        group_no: null,
        guest_id: null
    },

    oCustomerIDInfo: {
        member_id: null,
        guest_id: null
    },

    oCustomerInfo: {
        member_id: null,
        name: null,
        nick_name: null,
        group_name: null,
        group_no: null,
        email: null,
        phone: null,
        cellphone: null,
        birthday: null,
        additional_information: null,
        created_date: null
    },

    // @todo deprecated
    oMileageInfo: {
        available_mileage: null,
        returned_mileage: null,
        total_mileage: null,
        unavailable_mileage: null,
        used_mileage: null
    },

    // @todo deprecated
    oDepositInfo: {
        all_deposit: null,
        member_total_deposit: null,
        refund_wait_deposit: null,
        total_deposit: null,
        used_deposit: null
    },

    oPointInfo: {
        available_point: null,
        returned_point: null,
        total_point: null,
        unavailable_point: null,
        used_point: null
    },

    oCreditInfo: {
        all_credit: null,
        member_total_credit: null,
        refund_wait_credit: null,
        total_credit: null,
        used_credit: null
    },

    oCartList: {
        shop_no: null,
        product_no: null,
        additional_option: null,
        attached_file_option: null,
        basket_product_no: null,
        product_price: null,
        quantity: null,
        selected_product: null,
        variant_code: null
    },

    oCartInfo: {
        basket_price: null
    },

    oCartItemList: {
        basket_product_no: null,
        product_no: null,
        price: null,
        option_price: null,
        quantity: null,
        discount_price: null,
        variant_code: null,
        product_weight: null,
        display_group: null,
        quantity_based_discount: null,
        non_quantity_based_discount: null,
        product_volume: null,
        additional_option_values: null,
        product_bundle: null,
        product_bundle_no: null,
        option_id: null,
        product_name: null,
        product_image: null,
        option_value: null,
        shipping_fee_type: null
    },

    oCount: {
        count: 0
    },

    oShopInfo: {
        language_code: null,
        currency_code: null,
        timezone: null
    },

    oAddedProductToCart : {
        shop_no: null,
        category_no: null,
        quantity: null,
        additional_option_value: null,
        variant_code: null,
        product_bundle: null,
        prefaid_shipping_fee: null,
        attached_file_option: null,
        created_date: null,
        product_price: null,
        option_price: null,
        product_bundle_price: null,
        product_no: null,
        option_id: null,
        product_bundle_no: null,
        shipping_type : null,
        subscription: null,
        subscription_cycle: null,
        subscription_shipments_cycle_count: null,
        basket_product_no: null,
        additional_option_values: null,
        product_name: null,
        checked_products : null
    }
};
/**
 * 비동기식 데이터 - App Common ( 앱 공통정보 )
 */
CAPP_ASYNC_METHODS.aDatasetList.push('AppCommon');
CAPP_ASYNC_METHODS.AppCommon = {

    STORAGE_KEY: 'AppCommon_' + EC_SDE_SHOP_NUM,

    __sGuestId: null,

    isUse: function()
    {
        if (typeof EC_APPSCRIPT_SDK_DATA !== "undefined" && EC$.inArray('application', EC_APPSCRIPT_SDK_DATA) > -1) {
            return true;
        }

        return false;
    },

    restoreCache: function()
    {
        // sessionStorage 지원 여부 확인
        if (!window.sessionStorage) {
            return false;
        }

        try {
            var aStorageData = JSON.parse(window.sessionStorage.getItem(this.STORAGE_KEY));

            // expire 체크
            if (aStorageData.exp < Date.now()) {
                throw 'cache has expired.';
            }

            // 데이터 체크
            if (typeof aStorageData.data.guest_id === 'undefined') {
                throw 'Invalid cache data.';
            }

            // 데이터 복구
            this.__sGuestId = aStorageData.data.guest_id;

            return true;

        } catch (e) {
            // 복구 실패시 캐시 삭제
            this.removeCache();
            return false;
        }
    },

    removeCache: function()
    {
        // sessionStorage 지원 여부 확인
        if (!window.sessionStorage) {
            return;
        }
        // 캐시 삭제
        window.sessionStorage.removeItem(this.STORAGE_KEY);
    },

    setData: function(oData)
    {
        // sessionStorage 지원 여부 확인
        if (!window.sessionStorage) {
            return;
        }

        this.__sGuestId = oData.guest_id || '';

        try {
            sessionStorage.setItem(this.STORAGE_KEY, JSON.stringify({
                exp: Date.now() + (1000 * 60 * 10),
                data: this.getData()
            }));
        } catch (error) {
        }
    },

    execute: function()
    {
    },

    getData: function()
    {
        return {
            guest_id: this.__sGuestId
        };
    },

    setSpecData: function(oSpec, oData) {
        var aData = {};
        for (var prop in oSpec) {
            if (oData.hasOwnProperty(prop) === true) {
                aData[prop] = oData[prop];
            } else {
                aData[prop] = oSpec[prop];
            }
        }
        return aData;
    },

    setSpecDataMap: function(oSpec, oData, oMapData) {
        var aData = {};
        for (var prop in oSpec) {
            if (oData.hasOwnProperty(oMapData[prop]) === true) {
                aData[prop] = oData[oMapData[prop]];
            } else {
                aData[prop] = oSpec[prop];
            }
        }
        return aData;
    },

    // sdk function list
    getMemberID: function()
    {
        return CAPP_ASYNC_METHODS.member.getData().member_id;
    },

    getMemberInfo: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oMemberInfo, {group_no: CAPP_ASYNC_METHODS.member.getData().group_no, member_id: CAPP_ASYNC_METHODS.member.getData().member_id});
        } else {
            return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oMemberInfo, {guest_id: CAPP_ASYNC_METHODS.AppCommon.getData().guest_id});
        }
    },

    getCustomerIDInfo: function()
    {
        if (CAPP_ASYNC_METHODS.IS_LOGIN === true) {
            return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oCustomerIDInfo, {member_id: CAPP_ASYNC_METHODS.member.getData().member_id});
        } else {
            return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oCustomerIDInfo, {guest_id: CAPP_ASYNC_METHODS.AppCommon.getData().guest_id});
        }
    },

    getCustomerInfo: function()
    {
        var oMember = CAPP_ASYNC_METHODS.member.getData();
        if (oMember.created_date && typeof oMember.created_date === 'string') {
            oMember.created_date = oMember.created_date.replace(/(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}).+/, '$1');
        }
        return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oCustomerInfo, oMember);
    },

    // @todo deprecated
    getMileageInfo: function()
    {
        return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oMileageInfo, CAPP_ASYNC_METHODS.Mileage.getData());
    },

     // @todo deprecated
    getDepositInfo: function()
    {
        return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oDepositInfo, CAPP_ASYNC_METHODS.Deposit.getData());
    },

    getPointInfo: function()
    {
        var oMapData = {
            available_point: 'available_mileage',
            returned_point: 'returned_mileage',
            total_point: 'total_mileage',
            unavailable_point: 'unavailable_mileage',
            used_point: 'used_mileage'
        };

        return this.setSpecDataMap(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oPointInfo, CAPP_ASYNC_METHODS.Mileage.getData(), oMapData);
    },

    getCreditInfo: function()
    {
        var oMapData = {
            all_credit: 'all_deposit',
            member_total_credit: 'member_total_deposit',
            refund_wait_credit: 'refund_wait_deposit',
            total_credit: 'total_deposit',
            used_credit: 'used_deposit'
        };

        return this.setSpecDataMap(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oCreditInfo, CAPP_ASYNC_METHODS.Deposit.getData(), oMapData);
    },

    getCartList: function()
    {
        var oData = CAPP_ASYNC_METHODS.BasketProduct.getData();
        var oCartList = EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oCartList;
        var aCartList = [];

        for (var iKey in oData) {
            aCartList.push(this.setSpecData(oCartList, oData[iKey]));
        }

        return aCartList;
    },

    getCartInfo: function()
    {
        return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oCartInfo, CAPP_ASYNC_METHODS.Basketprice.getData());
    },

    getCartItemList: function()
    {
        var aCartItemList = [];
        if (typeof aBasketProductData === "undefined" && typeof aBasketProductOrderData === "undefined") {
            return aCartItemList;
        }

        //aBasketProductOrderData : 주문서
        //aBasketProductData : 장바구니
        var aData = (typeof aBasketProductOrderData !== "undefined") ? aBasketProductOrderData : aBasketProductData;

        var oMapData = {
            basket_product_no: 'basket_prd_no',
            product_no: 'product_no',
            price: 'product_price',
            option_price: 'opt_price',
            quantity: 'product_qty',
            discount_price: 'product_sale_price',
            variant_code: 'item_code',
            product_weight: 'product_weight',
            display_group: 'main_cate_no',
            quantity_based_discount: 'add_sale_related_qty',
            non_quantity_based_discount: 'add_sale_not_related_qty',
            product_volume: 'volume_size_serial',
            product_bundle: 'is_set_product',
            product_bundle_no: 'set_product_no',
            option_id: 'opt_id',
            product_name: 'product_name',
            product_image: 'product_image', //tiny 작은목록이미지
            option_value: 'option_str',
            shipping_fee_type : 'shipping_fee_type'
        };

        var idx = 0;
        var iOldBpPrdNo = null;
        var iNewBpPrdNo = null;
        for (var iKey in aData) {

            iNewBpPrdNo = aData[iKey].basket_prd_no;
            if (iOldBpPrdNo !== iNewBpPrdNo) {
                aCartItemList[idx] = this.setSpecDataMap(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oCartItemList, aData[iKey], oMapData);

                if (aCartItemList[idx]['product_volume'] !== '' && aCartItemList[idx]['product_volume'] !== null && aCartItemList[idx]['product_volume'] !== undefined) {
                    var aProductVolume = aCartItemList[idx]['product_volume'].split('|');
                    aCartItemList[idx]['product_volume'] = {
                        'product_width': parseFloat(aProductVolume[0]),
                        'product_height': parseFloat(aProductVolume[1]),
                        'product_length': parseFloat(aProductVolume[2]),
                    };
                } else {
                    aCartItemList[idx]['product_volume'] = {
                        'product_width': null,
                        'product_height': null,
                        'product_length': null,
                    };
                }

                if (aCartItemList[idx]['product_bundle_no'] == '0') { //세트상품번호 기본값 null 로 노출되도록
                    aCartItemList[idx]['product_bundle_no'] = null;
                }

                if (aCartItemList[idx]['product_image'] == '') {
                    aCartItemList[idx]['product_image'] = null;
                }

                if (aCartItemList[idx]['option_value'] !== null && aCartItemList[idx]['option_value'].length < 1) {
                    aCartItemList[idx]['option_value'] = null;
                }

                if (aData[iKey].custom_data != null) {
                    aCartItemList[idx]['additional_option_values'] = [];
                    aCartItemList[idx]['additional_option_values'].push(aData[iKey].custom_data);
                }
                idx++;

            } else {
                aCartItemList[idx - 1]['quantity'] += aData[iKey].product_qty;

                if (aData[iKey].custom_data != null) {
                    aCartItemList[idx - 1]['additional_option_values'].push(aData[iKey].custom_data);
                }
            }

            iOldBpPrdNo = iNewBpPrdNo;
        }

        return aCartItemList;
    },

    getCartCount: function()
    {
        return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oCount, CAPP_ASYNC_METHODS.Basketcnt.getData());
    },

    getCouponCount: function()
    {
        return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oCount, CAPP_ASYNC_METHODS.Couponcnt.getData());
    },

    getWishCount: function()
    {
        return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oCount, CAPP_ASYNC_METHODS.Wishcount.getData());
    },

    getShopInfo: function()
    {
        return this.setSpecData(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oShopInfo, {language_code: SHOP.getLanguage(), currency_code: SHOP.getCurrency(), timezone: SHOP.getTimezone()});
    },

    getCartMapData : function () {
        var oMapData = {
            shop_no: 'shop_no',
            category_no: 'main_cate_no',
            quantity: 'quantity',
            additional_option_value: 'option_add',
            variant_code: 'item_code',
            product_bundle: 'is_set_product',
            prefaid_shipping_fee: 'prd_detail_ship_type',
            attached_file_option: 'option_attached_file_info_json',
            created_date: 'ins_timestamp',
            product_price: 'product_price',
            option_price: 'opt_price',
            product_bundle_price: 'set_discount_price',
            product_no: 'product_no',
            option_id: 'opt_id',
            product_bundle_no: 'set_product_no',
            shipping_type : 'delvtype',
            subscription: 'is_subscription',
            subscription_shipments_cycle : 'subscription_cycle',
            subscription_shipments_cycle_count: 'subscription_cycle_count',
            basket_product_no: 'basket_prd_no',
            additional_option_values: 'custom_data',
            product_name: 'product_name',
            checked_products : 'is_prd'
        };
        return oMapData;
    },

    addCurrentProductToCart: function(mall_id, request_time, app_key, member_id, hmac)
    {
        return new Promise(function (resolve, reject) {
            new Promise(function (res, rej) {
                var aInfo = {
                    mall_id: mall_id,
                    request_time: request_time,
                    app_key: app_key,
                    request_member_id: member_id,
                    hmac: hmac
                };

                var proc = (typeof(set_option_data) === 'undefined') ? product_submit : product_set_submit;
                if(proc === 'undefined') reject();
                proc('app', '/exec/front/order/basket/', null, aInfo, res);
            }).then(function (data) {
                var oMapData =  CAPP_ASYNC_METHODS.AppCommon.getCartMapData();
                var aCurrentProductToCartResult = [];
                for (var index in data) {
                    aCurrentProductToCartResult[index] = CAPP_ASYNC_METHODS.AppCommon.setSpecDataMap(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oAddedProductToCart, data[index], oMapData);
                }
                resolve(aCurrentProductToCartResult);
            }).catch(function (data) {
                reject(data);
            })
        });
    },

    precreateOrder: function(mall_id, request_time, app_key, member_id, hmac)
    {
        return new Promise(function (resolve, reject) {
            var aInfo = {
                mall_id: mall_id,
                request_time: request_time,
                app_key: app_key,
                member_id: member_id,
                hmac: hmac,
                delv_type : (typeof(delvtype) == "undefined") ? sBasketDelvType : delvtype
            };
            new Promise(function (res, rej) {
                if(Basket.orderSelectBasket != undefined) {
                    var result = Basket.orderSelectBasket(this, aInfo['delv_type'], 'app', res);
                    if(result == false) rej();
                } else if (aAppBasketInsertItem == null) {
                    CAPP_ASYNC_METHODS.AppCommon.addCurrentProductToCart(mall_id, request_time, app_key, member_id, hmac).then(function(){
                          res();
                    }).catch(function () {
                          rej();
                    });
                } else {
                    res();
                }
            }).then(function () {
                EC$.post('/exec/front/order/reserve/', aInfo, function(data) {
                    var oMapData =  CAPP_ASYNC_METHODS.AppCommon.getCartMapData();
                    var aPrecreateOrderResult = [];
                    for (var index in data) {
                        aPrecreateOrderResult[index] = CAPP_ASYNC_METHODS.AppCommon.setSpecDataMap(EC_EXTERNAL_UTIL_APP_SPECINTERFACE.oAddedProductToCart, data[index], oMapData);
                    }
                    aPrecreateOrderResult['order_id'] = data['order_id'];
                    aPrecreateOrderResult['return_notification_url'] = data['return_noty_url'];

                    resolve(aPrecreateOrderResult);
                }, 'json');
            }).catch(function (data) {
                reject(data);
            })
        });
    }

};
