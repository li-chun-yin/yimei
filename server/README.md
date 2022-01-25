## 使用docker构建和使用服务

```
docker build -t lichunyin/yimei .
```	
```	
docker run -d -p8621:8621 lichunyin/yimei
```	

接口请求地址 http://127.0.0.1:8621/api

访问接口文档 http://127.0.0.1:8621/api-doc