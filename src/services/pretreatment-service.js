//通过工厂模式来创建服务
blog.factory('pretreatment',function($http,$q){
    function getData($url,$data)
    {
        //创建一个异步对象
        var deferred=$q.defer();
        var promise=deferred.promise;

        
        //拿到后台地址
        var url = $url;

        var postCfg = {
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            transformRequest: function ($data){
                return $.param($data);   //将对象数据装换 username=aaa,pass=111
            }
        };
        $http.post(url, $data,postCfg).success(function(response){
            deferred.resolve(response);
        })

        return promise;
    }

    return {
        getData:getData
    }
});