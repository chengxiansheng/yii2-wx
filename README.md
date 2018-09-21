# yii2-wx
基于yii2扩展微信公众号和小程序相关接口服务

# composer 安装
```
composer require "cheng/yii2-wx" "*"
```

# 应用结构
```
wx/
	/base					基础类
		...
	/common					小程序与微信公众号通用接口
	/template				模板消息统一接口
		/customerMsg		客服消息接口
	/config
		map-class.php		配置接口地图类
	/mini					小程序相关接口
		/aes				微信数据加密
		/check				内容安全检测接口
		/qrcode				小程序二维码
		/template			模板消息
		/user				用户信息
	/mp						公众号相关接口
		/menu				自定义菜单/个性化菜单设置
		/user				用户信息、标签、黑名单
		/qrcode				二维码、短连接生成
		/template			模板消息
		/oauth				微信网页授权
	Application.php			应用入口文件
```
