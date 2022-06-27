//開始年
const common_StartYear = 2020;

function setComboYear(objSelect, startYear = 0, lastYear = 0){

    if(startYear == 0){
        startYear = common_StartYear;
    }

    if(lastYear == 0){
        lastYear = (new Date()).getFullYear() + 1;
    }

    for(i=startYear;i<=lastYear;i++){
        let option = $("<option>");
        option.val(i).text(i + "年").appendTo(objSelect);
    }
}

function setComboMonth(objSelect){
    for(i=1;i<=12;i++){
        let option = $("<option>");
        option.val(("0" + i).slice(-2)).text(i + "月").appendTo(objSelect);
    }
}

function setComboDay(objSelectYear, objSelectMonth, objSelectDay){

    let year = objSelectYear.val();
    let month = objSelectMonth.val();
    let day = objSelectDay.val();
    
    objSelectDay.html("");

    let lastDays = (new Date(year, month, 0)).getDate();  //monthはインデックスなので-1+1=0
    let matsu = true;

    for(i=1;i<=lastDays;i++){
        let option = $("<option>");
        option.val(("0" + i).slice(-2)).text(i + "日").appendTo(objSelectDay);
        if(Number(day) == i){
            matsu = false;
        }
    }

    if(matsu){
        objSelectDay.val(lastDays);
    } else {
        objSelectDay.val(("0" + day).slice(-2));
    }
}

function timeFormatCheck(sTime){

    if(sTime == ""){
        return true;
    } else {
        return sTime.match(/^([01][0-9]|2[0-3]|[0-9]):[0-5][0-9]$/);
    }

}

function timeFormatCheck2(sTime){

    if(sTime == ""){
        return true;
    } else {
        return sTime.match(/^[0-9]{1,2}:[0-5][0-9]$/);
    }

}

function timeNumberChange(sNumber){
    sNumber = hankaku2Zenkaku(sNumber.trim());
    if(sNumber == ""){
        return "";
    } else {
        if(sNumber.indexOf(":") >= 0){
            //コロンがある場合
            if(sNumber.match(/^([01][0-9]|2[0-3]):[0-5][0-9]$/)){
                return sNumber;
            } else if(sNumber.match(/^[0-9]:[0-5][0-9]$/)){
                return "0" + sNumber;
            } else {
                return sNumber;
            }
        } else {
            //コロンがない場合
            //全て数値かどうか
            if(!sNumber.match(/^[0-9]{1,4}$/)){
                return sNumber;
            } else if(sNumber.length == 4) {
                if(sNumber.match(/^([01][0-9]|2[0-3])[0-5][0-9]$/)){
                    return sNumber.substr(0, 2) + ":" + sNumber.substr(2, 2);
                } else {
                    return sNumber;
                }
            } else if(sNumber.length == 3) {
                if(sNumber.match(/^[0-9][0-5][0-9]$/)){
                    return "0" + sNumber.substr(0, 1) + ":" + sNumber.substr(1, 2);
                } else {
                    return sNumber;
                }
            } else {
                if(Number(sNumber) < 60) {
                    return "00:" + ("0" + sNumber).slice(-2);
                } else {
                    return sNumber;
                }
            }
        }

    }
}

function timeNumberChange2(sNumber){
    sNumber = hankaku2Zenkaku(sNumber.trim());
    if(sNumber == ""){
        return "";
    } else {
        if(sNumber.indexOf(":") >= 0){
            //コロンがある場合
            if(sNumber.match(/^[0-9]{1,2}:[0-5][0-9]$/)){
                return sNumber;
            } else {
                return sNumber;
            }
        } else {
            //コロンがない場合
            //全て数値かどうか
            if(!sNumber.match(/^[0-9]{1,4}$/)){
                return sNumber;
            } else if(sNumber.length == 4) {
                if(sNumber.match(/^[0-9]{1,2}[0-5][0-9]$/)){
                    return sNumber.substr(0, 2) + ":" + sNumber.substr(2, 2);
                } else {
                    return sNumber;
                }
            } else if(sNumber.length == 3) {
                if(sNumber.match(/^[0-9][0-5][0-9]$/)){
                    return sNumber.substr(0, 1) + ":" + sNumber.substr(1, 2);
                } else {
                    return sNumber;
                }
            } else {
                if(Number(sNumber) < 60) {
                    return "0:" + ("0" + sNumber).slice(-2);
                } else {
                    return sNumber;
                }
            }
        }

    }
}

function hankaku2Zenkaku(str) {
    let strRet
    strRet = str.replace(/[０-９]/g, function(s) {
        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
    });

    return strRet.replace(/：/g, ':');
}

function getHMToMin(flg, HM){
    if(HM == "" || !timeFormatCheck(HM)){
        return -1441;
    } else {
        let sp = HM.split(":");
        return Number(sp[0]) * 60 + Number(sp[1]) + (flg * 1440);
    }
}

function getMinToHM(HM){
    if(HM == -1441){
        return "";
    } else {
        return ("0" + Math.floor(HM / 60)).slice(-2) + ":" + ("0" + (HM % 60)).slice(-2);
    }
}

function getMinToHM2(HM){
    if(HM == -1441){
        return "";
    } else {
        return Math.floor(HM / 60) + ":" + ("0" + (HM % 60)).slice(-2);
    }
}

function dispLoading(sec = 10)
{   
    var waitTime = sec * 1000;
	var dispMsg = "<div class='loadingMsg'>Now Loading...</div>";
    var len = Number($("#loading").length);
	if( len == 0){
        $("body").append("<div id='loading'>" + dispMsg + "</div>");
	}
  	setTimeout("removeLoading()", waitTime);
}
function removeLoading()
{
    $("#loading").remove();
}

