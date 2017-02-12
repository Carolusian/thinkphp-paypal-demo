## 设置

* 注册 Paypal Sandbox 账户，并在 tools -> API credentials 里设置 NVP/SOAP API integration
* 将上一步得到的 API Signature 设置放入 Application/Home/Conf/config.php
* 更新 Application/Home/Conf/config.php 中的 Return URL 和 Cancel URL 地址
* 搭建 Web Server 并访问 http://localhost/
* Demo 代码在 Application/Home/Controller/IndexController.class.php 中
