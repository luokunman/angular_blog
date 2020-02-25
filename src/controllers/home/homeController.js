/***********************************************************
 * 项目名称： blog
 * 内容摘要： rouken博客
 * 其他说明： angular开发博客
 * 完成日期： 2019/7/13 16:38:46
 * 当前版本： 1.0
 * 采   用： ES5
 * 企   鹅： 1013665172
/* ====================================================================================================== *\
 *                                                                                                        *
 *  作    者 ： rouken                                                                                    *
 *  创建时间 ： 2019/7/13 21:30:30 [星期六]                                                                   *
 *  修改时间 ： 2019/7/13 21:30:30 [星期六]                                                                                   *
 *                                                                                                                            *
 *    _________            __           __             _________   ____          __________    __________                     *
 *  |\  _____   \ \      |\   \       /  / |          |\        \ | \  \        | \   ____  \ |\         \                    *
 *  \ \ \    /  /  /     \ \   \     /  /  /          \ \   __   \ \ \  \        \ \  \__/\  \ \ \   _____\                   *
 *   \ \ \__/  /  /       \ \   \___/  /  /            \ \  \/\  /_ \ \  \        \ \  \ \ \  \ \ \  \____|                   *
 *    \ \   __ \  \        \ \    __  /  /              \ \   __   \ \ \  \        \ \  \ \ \  \ \ \  \    ___                *
 *     \ \  \_ _ \  \       \ \   \  \ \  \              \ \  \/|   \ \ \  \        \ \  \ \ \  \ \ \  \   \  \               *
 *      \ \  \  \ \  \       \ \   \  \ \  \              \ \        \ \ \  \_______ \ \  \_\_\  \ \ \  \_/_\  \              *
 *       \ \__\  \ \__\       \ \ __\  \ \  \              \ \________\ \ \_________\ \ \_________\ \ \_________\             *
 *        \|__|   \ |__|       \_|__|   \ |__|              \|________|  \|__________| \|_________|  \|_________|             *
\* ====================================================================================================== */



var url = `${dataApi}/main.php`;
console.log(url);
blog.controller("homeController", function ($scope, $http, $rootScope, $cookieStore, pretreatment){
    if (!$cookieStore.get("userid")) {
        $rootScope.username = "welcome to rouken's blog";
        $cookieStore.put("id", "0");
        $rootScope.email="1013665172@qq.com";
        $rootScope.avatar = "logo.png";
        $rootScope.isUser = false;
        $rootScope.noUser = true;
    } else {
        // decodeURI()解码
        $rootScope.username = decodeURI($cookieStore.get("username"));
        $rootScope.avatar = decodeURI($cookieStore.get("avatar"));
        $rootScope.id = decodeURI($cookieStore.get("id"));  //初始id = 0
        $rootScope.userid = decodeURI($cookieStore.get("userid"));//登录用户id
        $rootScope.content = decodeURI($cookieStore.get("content"));
        $rootScope.email = decodeURI($cookieStore.get("email"));
        $rootScope.isUser = true;
        $rootScope.noUser = false;
    }
    
    //找图片
    $http({
        method:"get",
        url:`${url}?action=userData&userid=${$cookieStore.get("userid")}`,
    }).then(function(res){
        if(res.data.length==0){
            return false;
        }

        $scope.gallery_count = res.data.length;
        $scope.gallerys = res.data;
    })

    //找图片类型
    $http({
        method:"get",
        url:`${url}?action=imgCate`,
    }).then(function(res){
        if(res.data.length == 0){
            console.log(`没有找到imgcate`)
            return false;
        }
        $scope.imgcate = res.data;

    })

    //找文章
    $http({
        method:"get",
        url:`${url}?action=artclelist&userid=${$cookieStore.get("userid")}`,
    }).then(function(res){
        if(res.data.length==0){
            return false;
        }
        $scope.articles = res.data;
    })
    $scope.titleSearch = function(){
        $http({
            method:"get",
            url: `${url}?action=title_keywords&keywords=${$scope.title_keywords}`,
        }).then(function(res){
            console.log(res.data)
        })
    }
    //加载完成
    $scope.$on('ngRepeatFinished', function (ngRepeatFinishedEvent) {

        /* Start Of Isotope
        ================================================*/
        $scope.workTab =  function () {
                /* activate jquery isotope */
                var $container = $('#posts').isotope({
                    itemSelector: '.item',
                    isFitWidth: true
                });

                $container.isotope({ filter: '*' });

                // filter items on button click
                $('#filters').on('click', 'button', function () {
                    var filterValue = $(this).attr('data-filter');
                    $container.isotope({ filter: filterValue });
                });
                $('.tab-button').on("click", function () {

                    //console.log("Clicked");
                    $('.tab-button.active').removeClass('active');
                    $(this).addClass('active');
                });
                var $grid = $('#posts').isotope({
                    itemSelector: '.item',
                    percentPosition: true,
                });
        }
            var width, height, largeHeader, canvas, ctx, circles, target, animateHeader = true;


            // Main    ---------circle-effect.js
            addListeners();

            initHeader();
            function initHeader() {
                width = window.innerWidth;
                height = window.innerHeight;
                target = { x: 0, y: height };

                largeHeader = document.getElementById('page-head-effect');
                if (largeHeader) {
                    largeHeader.style.height = height + 'px';
                }
                canvas = document.getElementById('demo-canvas');
                if (canvas) {
                    canvas.width = width;
                    canvas.height = height;
                    ctx = canvas.getContext('2d');
                }

                // create particles
                circles = [];
                for (var x = 0; x < width * 0.5; x++) {
                    var c = new Circle();
                    circles.push(c);
                }
                animate();
            }

            // Event handling
            function addListeners() {
                window.addEventListener('scroll', scrollCheck);
                window.addEventListener('resize', resize);
            }

            function scrollCheck() {
                if (document.body.scrollTop > height) animateHeader = false;
                else animateHeader = true;
            }

            function resize() {
                width = window.innerWidth;
                height = window.innerHeight;
                if (largeHeader) {
                    largeHeader.style.height = height + 'px';
                    canvas.width = width;
                    canvas.height = height;
                }
            }

            function animate() {
                if (animateHeader) {
                    if (ctx) {
                        ctx.clearRect(0, 0, width, height);
                    }
                    for (var i in circles) {
                        circles[i].draw();
                    }
                }
                requestAnimationFrame(animate);
            }

            // Canvas manipulation
            function Circle() {
                var _this = this;

                // constructor
                (function () {
                    _this.pos = {};
                    init();
                })();

                function init() {
                    _this.pos.x = Math.random() * width;
                    _this.pos.y = height + Math.random() * 100;
                    _this.alpha = 0.1 + Math.random() * 0.3;
                    _this.scale = 0.1 + Math.random() * 0.3;
                    _this.velocity = Math.random();
                }

                this.draw = function () {
                    if (_this.alpha <= 0) {
                        init();
                    }
                    _this.pos.y -= _this.velocity;
                    _this.alpha -= 0.0005;
                    if (ctx) {
                        ctx.beginPath();
                        ctx.arc(_this.pos.x, _this.pos.y, _this.scale * 10, 0, 2 * Math.PI, false);
                        ctx.fillStyle = 'rgba(153,153,153,' + _this.alpha + ')';
                        ctx.fill();
                    }
                };
            }
    });
})
blog.controller("registerController", function ($scope, $rootScope,$state, $http, pretreatment, Md5, Base64, Sha1){
    if (typeof $cookieStore.userid == "undefined") {
        $cookieStore.remove("username");
        $cookieStore.remove("avatar");
        $cookieStore.put("id", "0");
        $cookieStore.remove("content");
        $cookieStore.remove("userid");
        $cookieStore.remove("email");
        $rootScope.avatar = "logo.png";
        $rootScope.isUser = false;
        $rootScope.noUser = true;
    }

    $rootScope.id = decodeURI($cookieStore.get("id"));

    // decodeURI()解码
    $rootScope.id = decodeURI($cookieStore.get("id"));

    function regTrim(data){
        if(!data){
            return false;
        }
    }
    //封装的注册数据对象
    $scope.registerData = {
        username: $scope.username,
        password: $scope.password,
        confpassword: $scope.confpassword,
        email: $scope.email,
        name:"register"
    }
    $scope.exist = false;   //用户是否在数据库存在
    $scope.inconsistency = false; //密码是否一致
    // 验证用户输入
    $scope.registerReg = function () {
        regTrim($scope.registerData.username);
        $scope.regUsername = {
            username: $scope.registerData.username,
            name:"regusername"
        }
        pretreatment.getData(url, $scope.regUsername).then(function (res) {
            if(res.id){
                $scope.exist = true;
                return false;
            }else{
                $scope.exist = false;
            }
        });
        if ($scope.registerData.password != $scope.registerData.confpassword){
            $scope.inconsistency = true;
        }else{
            $scope.inconsistency = false;;
        }
    }


    // 发送数据
    $scope.sendData = function(){
        regTrim($scope.registerData.username);

        regTrim($scope.registerData.password);

        regTrim($scope.registerData.confpassword);
        
        regTrim($scope.registerData.email);

        if ($scope.exist || $scope.inconsistency){
            return false;
        }


        pretreatment.getData(url, $scope.registerData).then(function(res){
            if(res){
                $scope.registerData = {
                    username: "",
                    password: "",
                    email: "",
                }
                $state.go("login");
                return false;
            }

        });
    }
})
blog.controller("loginController", function ($scope, $rootScope, $state, $http, pretreatment, Md5, Base64, Sha1, $cookieStore){
    if (typeof $cookieStore.userid == "undefined") {
        $cookieStore.remove("username");
        $cookieStore.remove("avatar");
        $cookieStore.put("id", "0");
        $cookieStore.remove("content");
        $cookieStore.remove("userid");
        $cookieStore.remove("email");
        $rootScope.avatar = "logo.png";
        $rootScope.username = "welcome to rouken's blog";
        $rootScope.isUser = false;
        $rootScope.noUser = true;
    }

    $rootScope.id = decodeURI($cookieStore.get("id"));

    $scope.exist = false;   //用户是否在数据库存在
    $scope.wrong = false;   //用户密码是否正确
    
    function regTrim(data) {
        if (!data) {
            return false;
        }
    }
    //登录成功回调函数
    function loginsuccess(res){

        $cookieStore.remove("username");
        $cookieStore.remove("id");
        $cookieStore.remove("avatar");

        $cookieStore.put("username", res.username);
        $cookieStore.put("userid", res.userid);
        $cookieStore.put("avatar", res.avatar);
        $cookieStore.put("content", res.content);
        $cookieStore.put("email", res.email);
        $cookieStore.put("gallery", res.gallery);
        $scope.wrong = false;   //用户密码是否正确
        $state.go("home");
        return false;
    }
    //封装的注册数据对象
    $scope.loginData = {
        username: $scope.username,
        password: $scope.password,
        name: "login"
    }

    //验证用户是否存在

    $scope.registerReg = function () {
        regTrim($scope.loginData.username);
        $scope.existUsername = {
            username: $scope.loginData.username,
            name: "existusername"
        }
        pretreatment.getData(url, $scope.existUsername).then(function (res) {
            if(!res){
                $scope.exist = true;   //用户在数据库不存在
                return false;
            }else{
                $scope.exist = false;   //用户在数据库存在
            }


        });
    }
    // 发送数据
    $scope.sendData = function () {
        regTrim($scope.loginData.username);

        regTrim($scope.loginData.password);
        pretreatment.getData(url, $scope.loginData).then(function (res) {
            if(res){
                // 登录成功
                loginsuccess(res);
            }else{
                console.log(123213);
                $scope.wrong = true;   //用户密码是否正确
            }
        });
    }
})
blog.controller("linksController", function ($scope, $rootScope, $state, $cookieStore, $http, pretreatment,page){
    if (!$cookieStore.get("userid")) {
        $rootScope.username = "welcome to rouken's blog";
        $cookieStore.put("id", "0");
        $rootScope.email = "1013665172@qq.com";
        $rootScope.avatar = "logo.png";
        $rootScope.isUser = false;
        $rootScope.noUser = true;
    } else {
        // decodeURI()解码
        $rootScope.username = decodeURI($cookieStore.get("username"));
        $rootScope.avatar = decodeURI($cookieStore.get("avatar"));
        $rootScope.id = decodeURI($cookieStore.get("id"));  //初始id = 0
        $rootScope.userid = decodeURI($cookieStore.get("userid"));//登录用户id
        $rootScope.content = decodeURI($cookieStore.get("content"));
        $rootScope.email = decodeURI($cookieStore.get("email"));
        $rootScope.isUser = true;
        $rootScope.noUser = false;
    }


    linkUrl = `${url}`;


    //总条数
    $http({
        method: "get",
        url: `${url}?action=linksCount`,
    }).then(function (res) {
        //食品总数
        $scope.totalItems = res.data[0].count;
    });

    //找友情链接

    $scope.currentPage = 1;//当前页码数
    $scope.bigTotalItems = 3;//每页显示几条数据
    $scope.maxSize = 2;//中间显示有几个分页

    page.getData(linkUrl,$scope.currentPage,$scope.bigTotalItems,'links').then(function(res){
        $scope.linklist = res;
    })

    $scope.pageChanged = function(){
        page.getData(linkUrl, $scope.currentPage, $scope.bigTotalItems, 'links').then(function(res){
            console.log(res);
            $scope.linklist = res;
        })
    }
    //加载完成
    $scope.$on('ngRepeatFinished', function (ngRepeatFinishedEvent) {
        new WOW().init();
    });



    //加载完成
    $scope.$on('ngRepeatFinished', function (ngRepeatFinishedEvent) {

        /* Start Of Isotope
        ================================================*/
        $scope.workTab = function () {
            /* activate jquery isotope */
            var $container = $('#posts').isotope({
                itemSelector: '.item',
                isFitWidth: true
            });

            $container.isotope({ filter: '*' });

            // filter items on button click
            $('#filters').on('click', 'button', function () {
                var filterValue = $(this).attr('data-filter');
                $container.isotope({ filter: filterValue });
            });
            $('.tab-button').on("click", function () {

                //console.log("Clicked");
                $('.tab-button.active').removeClass('active');
                $(this).addClass('active');
            });
            var $grid = $('#posts').isotope({
                itemSelector: '.item',
                percentPosition: true,
            });
        }
        var width, height, largeHeader, canvas, ctx, circles, target, animateHeader = true;


        // Main    ---------circle-effect.js
        addListeners();

        initHeader();
        function initHeader() {
            width = window.innerWidth;
            height = window.innerHeight;
            target = { x: 0, y: height };

            largeHeader = document.getElementById('page-head-effect');
            if (largeHeader) {
                largeHeader.style.height = height + 'px';
            }
            canvas = document.getElementById('demo-canvas');
            if (canvas) {
                canvas.width = width;
                canvas.height = height;
                ctx = canvas.getContext('2d');
            }

            // create particles
            circles = [];
            for (var x = 0; x < width * 0.5; x++) {
                var c = new Circle();
                circles.push(c);
            }
            animate();
        }

        // Event handling
        function addListeners() {
            window.addEventListener('scroll', scrollCheck);
            window.addEventListener('resize', resize);
        }

        function scrollCheck() {
            if (document.body.scrollTop > height) animateHeader = false;
            else animateHeader = true;
        }

        function resize() {
            width = window.innerWidth;
            height = window.innerHeight;
            if (largeHeader) {
                largeHeader.style.height = height + 'px';
                canvas.width = width;
                canvas.height = height;
            }
        }

        function animate() {
            if (animateHeader) {
                if (ctx) {
                    ctx.clearRect(0, 0, width, height);
                }
                for (var i in circles) {
                    circles[i].draw();
                }
            }
            requestAnimationFrame(animate);
        }

        // Canvas manipulation
        function Circle() {
            var _this = this;

            // constructor
            (function () {
                _this.pos = {};
                init();
            })();

            function init() {
                _this.pos.x = Math.random() * width;
                _this.pos.y = height + Math.random() * 100;
                _this.alpha = 0.1 + Math.random() * 0.3;
                _this.scale = 0.1 + Math.random() * 0.3;
                _this.velocity = Math.random();
            }

            this.draw = function () {
                if (_this.alpha <= 0) {
                    init();
                }
                _this.pos.y -= _this.velocity;
                _this.alpha -= 0.0005;
                if (ctx) {
                    ctx.beginPath();
                    ctx.arc(_this.pos.x, _this.pos.y, _this.scale * 10, 0, 2 * Math.PI, false);
                    ctx.fillStyle = 'rgba(153,153,153,' + _this.alpha + ')';
                    ctx.fill();
                }
            };
        }
    });
})
blog.controller("aboutController",function($scope,$rootScope,$state,$cookieStore,$http){
    if (!$cookieStore.get("userid")) {
        $rootScope.username = "welcome to rouken's blog";
        $cookieStore.put("id", "0");
        $rootScope.email = "1013665172@qq.com";
        $rootScope.avatar = "logo.png";
        $rootScope.isUser = false;
        $rootScope.noUser = true;
    } else {
        // decodeURI()解码
        $rootScope.username = decodeURI($cookieStore.get("username"));
        $rootScope.avatar = decodeURI($cookieStore.get("avatar"));
        $rootScope.id = decodeURI($cookieStore.get("id"));  //初始id = 0
        $rootScope.userid = decodeURI($cookieStore.get("userid"));//登录用户id
        $rootScope.content = decodeURI($cookieStore.get("content"));
        $rootScope.email = decodeURI($cookieStore.get("email"));
        $rootScope.isUser = true;
        $rootScope.noUser = false;
    }

    //找图片
    $http({
        method: "get",
        url: `${url}?action=userExp&userid=${$cookieStore.get("userid")}`,
    }).then(function (res) {
        $scope.userExp = res.data;
    })

})