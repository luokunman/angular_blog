function config($stateProvider,$urlRouterProvider,$httpProvider)
{
    //404
    $urlRouterProvider.otherwise("home");
    // 设置路由表
    $stateProvider
        .state("home",{
            url:"/home",
            controller:"homeController",
            templateUrl:"../src/views/home/index.html"
        })
        .state("register", {
            url: "/register",
            controller: "registerController",
            templateUrl: "../src/views/home/register.html"
        })
        .state("login",{
            url:"/login",
            controller:"loginController",
            templateUrl:"../src/views/home/login.html"
        })
        .state("friend_link",{
            url:"/friend_link",
            controller:"linksController",
            templateUrl:"../src/views/home/friend_link.html"
        })
        .state("blog-single",{
            url:"/blog-single",
            controller:"singleController",
            templateUrl:"../src/views/home/blog-single.html"
        })
        .state("aboutme",{
            url:"/aboutme",
            controller:"aboutController",
            templateUrl:"../src/views/home/aboutme.html"
        })
}
//路由注入
blog.config(config).run();