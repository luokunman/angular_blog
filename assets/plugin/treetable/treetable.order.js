/*
 *
 * TreeTable 0.1 - Client-side TreeTable Viewer!
 * @requires jQuery v1.3
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 *
 */
 
(function($){
  $.extend({
      treetable: new function() {
       
          this.defaults = {
              id_col: 0,
              parent_col: 1,
              handle_col: 2,
              order_col: -1,
              open_img: "lib/plugin/treetable/images/minus.gif",
              close_img: "lib/plugin/treetable/images/plus.gif",
              expanded: true//@author GaoBing
          };
           
          //jquery的trim处理不了 产生的"空格"
          function trim(str){
              return str.replace(/(^[\s\xA0]*)|([\s\xA0]*$)/g, "");
          }
           
          this.construct = function(settings){
           
              if(this.size()!=1)return;//只处理一个表格
               
              if(this[0].tagName.toUpperCase()!="TBODY")return;//只应用于tbody
           
              var config = $.extend({}, $.treetable.defaults, settings);
               
              if(config.id_col==null || config.parent_col==null || config.handle_col==null ) return;
               
              var $this = $(this);
              var tr_arr = new Array();
              var tr_sort = new Array();

              //构建行对象数组
              $this.find("tr").each(function(){
                  var id = $.trim($(this).find("td:eq("+config.id_col+")").text());
                  var parent = $.trim($(this).find("td:eq("+config.parent_col+")").text());
                  tr_arr.push({'id':id,'parent':parent,'level':0,'node':'leaf','expanded':config.expanded,'obj':$(this)});
              });
              var len = tr_arr.length;
              var level = 0;
               
              /*
              检查tr_arr中的每一行的父行是否再tr_sort中，
              如果有则插入到tr_sort的父行后，从tr_arr中删除
              直到tr_arr都为null,生成排好序的tr_sort
              */
              while(len>0){
                  for(var i=0;i<tr_arr.length;i++){
                      var o = tr_arr[i];
                       
                      if(o==null)continue;
                       
                      if(o.parent==""){//根行直接压入tr_sort
                          tr_sort.push(o);
                          tr_arr[i]=null;
                          len=len-1;
                      }else{
                          if (tr_sort.length > 0){
                              for(var j=0;j<tr_sort.length;j++){
                                  if(tr_sort[j].id==o.parent){
                                      o.level = tr_sort[j].level+1;//从父行累计生成层次level
                                      tr_sort[j].node='node';
                                      tr_sort.splice(j+1,0,o);//数组插入
                                      tr_arr[i]=null;
                                      len=len-1;
                                      break;
                                  }
                              }
                          }else{
                              for(var k=0;k<tr_arr.length;k++){
                                  var ok = tr_arr[k];
                                  if(ok == null) continue;
                                  if (o.id != ok.parent && ok.parent != ""){
                                      ok.level = tr_arr[k].level+1;
                                      tr_sort.push(ok);
                                      tr_arr[i]=null;
                                      len=len-1;
                                  }else{
                                      if (tr_sort[k]){
                                          o.level = tr_sort[k].level+1;//从父行累计生成层次level
                                          tr_sort[k].node='node';
                                          tr_sort.splice(k+1,0,o);//数组插入
                                          tr_arr[i]=null;
                                          len=len-1;
                                          break;
                                      }
                                  }
                              }
                          }
                      }
                  }
                  level=level+1;
              }//while
   
               
               
               
              //展开事件动作函数
              var fn_click = function(){

                  var id = trim($(this).parent().parent().find("td:eq("+config.id_col+")").text());//获取当前行ID
                  var v = -1;
                  for(var j=0;j<tr_sort.length;j++){
                      var o = tr_sort[j];
                      if(o.id==id){//在tr_sort找到行对象
                   
                          if(o.node=='leaf')return;
                           
                          v = o.level;
                          var img = o.obj.find("td:eq("+config.handle_col+") img")[0];
                           
                          if(!o.expanded){//通过图标判断是展开还是收起
                              img.src=config.open_img;
                              o.expanded=true;
                          }else{
                              img.src=config.close_img;
                              o.expanded=false;
                          }
                           
                          var show = o.expanded;
                          var f = false;//父行收起标志
                          var tmp = 0;//父行的层次
                           
                          for(var i=j+1;i<tr_sort.length;i++){//根据level更新后续的子行
                              o = tr_sort[i];
                               
                              var img = o.obj.find("td:eq("+config.handle_col+") img")[0];
                               
                              var t = !o.expanded;//判断是否是收起状态
                               
                              if(o.level>v && show){//展开操作
                                  if(!f&&!t){//父行未收起，且当前行是展开状态
                                      o.obj.show();
                                  }else if(!f&&t){//父行未收起，且当前行是收起状态
                                      tmp = o.level;
                                      f = true;
                                      o.obj.show();
                                  }else if(f&&o.level<=tmp){//同级的前一行是收起状态
                                      if(!t){
                                          f=false;
                                      }else{
                                          tmp = o.level;
                                      }
                                      o.obj.show();
                                  }else{
                                      ;
                                  }
                           
                              }else if(o.level>v && !show){//收起操作则隐藏所以子行
                                  o.obj.hide();
                              }else if(o.level<=v){//到达非子行，处理完毕
                                  break;
                              }
                          }
                       
                          break;
                      }
                  }
              };
           
              //重新绘制表格，添加展开动作图标
              for(var j=tr_sort.length-1;j>-1;j--){//prepend插入tbody内需使用反序
                  var o = tr_sort[j];
                   
                  var img = $("<img src='"+config.open_img+"'>");
                  img.click(fn_click);
                   
                  var tr=o.obj.find("td:eq("+config.handle_col+")");
                   
                  //避免重复添加图标
                  var imgEle = tr.find("img");
                  if(imgEle.length == 0){
                      tr.prepend(" ");
                      tr.prepend(img);
                      var s = new Array((o.level+1)*5).join(" ");//生成缩进空格
                      tr.prepend(s);
                       
                      $this.prepend(o.obj);
                  }
                   
              }//for
   
           
              /*
               * @author GaoBing
               * 是否展开 当expanded为false时，父节点收缩 
               * */
              if(tr_sort.length > 0){
                  for(var i=0;i<tr_sort.length;i++){
                      var o = tr_sort[i];//行对象
                      var img = o.obj.find("td:eq("+config.handle_col+") img");//父节点图标元素
                      if(o.expanded == false){//收缩
                          //检查是否有父节点，如有父节点则隐藏本行节点
                          if (o.parent != ""){
                              o.obj.hide();//隐藏子节点
                          }
                          //检查是否有子节点，有子节点则替换图标
                          for(var j=0;j<tr_sort.length;j++){
                              if (tr_sort[j].parent != "" && tr_sort[j].parent == o.id){//有子节点
                                  $(img).attr("src",config.close_img);//替换父节点图标
                              }
                          }
                      }
                  }
              }
               
               
          }//construct
      }//treetable

  });
   
  $.fn.extend({
      treetable: $.treetable.construct
  });
   
})(jQuery);


/**
* @author GaoBing
* 功能：对树状表格进行排序 父节点在前 子节点在后
**/
function treetable_sort(tableId,parentColumn){
   
  var trs = new Array();
   
  //拿到所有行
  $("#" + tableId + " tbody tr[role='row']").each(function(index){
      trs[index] = $(this).clone(true);
  });
   
  //清空之前的行数据
  $("#" + tableId + " tbody").empty();

  // console.log(trs,tableId,parentColumn)
   
  //递归
  sort_tr_root(trs,tableId,parentColumn);
   
}

/*根节点排序*/
function sort_tr_root(trs,tableId,parentColumn){
  var rootArr = new Array();
  for(var i=0;i<trs.length;i++){
      if (null != trs[i]){
          var trsData = trs[i].attr("data");
          var trsDataObj = eval('(' + trsData + ')');
          var trsId = trsDataObj['id'];
          var trsParentId = trsDataObj[ parentColumn ];
          if (null == trsParentId || trsParentId == "" || undefined == trsParentId){//最外层的根
              trs[i].attr("id",trsId);
              $("#" + tableId + " tbody").append(trs[i]);
              rootArr.push(trs[i]);
              trs[i] = null;
          }
      }
  }
   
  //子节点排序
  var treeData = changeDataToMap(trs);
  var result = createSearchMap(trs,parentColumn);
  for(var i=0;i<rootArr.length;i++){
      var rootData = rootArr[i].attr("data");
      var rootDataObj = eval('(' + rootData + ')');
      var rootId = rootDataObj['id'];
      var rootParentId = rootDataObj[ parentColumn ];
      buildChild(tableId,treeData,result,rootId,rootParentId);
  }
   
}

function createSearchMap(data,parentColumn){
  //创建搜索键值对
  var result = {};
  for(var i=0;i<data.length;i++) {
      if (null != data[i]){
          var trsData = data[i].attr("data");
          var trsDataObj = eval('(' + trsData + ')');
          var trId = trsDataObj['id'];
          var trParentId = trsDataObj[parentColumn];
          if(trParentId){
              if(!result[trParentId] && trParentId != null && trParentId != ""){
                  result[trParentId] = [];
              }
              result[trParentId].push(trId);
          }
      }
  }
  return result;
}

function changeDataToMap(trs){
  var deepMap = new Array();
  for(var i=0;i<trs.length;i++){
      if (null != trs[i]){
          var trsData = trs[i].attr("data");
          var trsDataObj = eval('(' + trsData + ')');
          var trId = trsDataObj['id'];
          if(trId){
              deepMap[trId] = trs[i];
          }
      }
  }
  return deepMap;
}


function buildChild(tableId,treeData,result,id,parentId){
  if (parentId){
      var trObj = treeData[id];
      trObj.attr("id",id);
      $("#" + tableId + " tbody tr[role=row][id=" + parentId + "]").after(trObj);
  }
  var childrenIds = result[id];
  if(childrenIds){
      for(var i=0;i<childrenIds.length;i++){
          buildChild(tableId,treeData,result,childrenIds[i],id);
      }
  }
}


/*判断是否包含某个元素*/
Array.prototype.contains = function (element) { // 利用Array的原型prototype点出一个我想要封装的方法名contains
  for (var i = 0; i < this.length; i++) {
      if (this[i] == element) { // 如果数组中某个元素和你想要测试的元素对象element相等，则证明数组中包含这个元素，返回true
          return true;
      }
  }
};

/**
* @author GaoBing
* 功能：表格树 供datatable调用接口
**/
function initTreeTable(obj){
   
  //参数
  var tableId = obj.tableId;//表格ID
  var tbodyId = obj.tbodyId;//表格中tbodyID
  var isExpanded = obj.isExpanded;//是否展开所有节点
  var viewConfig = obj.viewConfig;//展示配置
  var parentColumn = obj.parentColumn;//父列
   
  //排序 父节点排在子节点前面
  treetable_sort(tableId,parentColumn);
   
  //默认属性
  $.treetable.defaults={
      id_col: viewConfig[0],//ID td列 {从0开始}
      parent_col: viewConfig[1],//父ID td列
      handle_col: viewConfig[2],//操作展开操作的 td列
      open_img: "lib/plugin/treetable/images/minus.gif",//展开时图标
      close_img: "lib/plugin/treetable/images/plus.gif",//收缩时图标
      expanded: isExpanded//true为展开，false为收缩
  };
   
  //生成树
  $("#" + tbodyId).treetable();
   
  //隐藏数据列
  $("#" + tbodyId + " tr").find("td:eq(" + viewConfig[0] + ")").hide();
  $("#" + tbodyId + " tr").find("td:eq(" + viewConfig[1] + ")").hide();
  $("#" + tableId + " tr:eq(0)").find("th:eq(" + viewConfig[0] + ")").hide();
  $("#" + tableId + " tr:eq(0)").find("th:eq(" + viewConfig[1] + ")").hide();
}