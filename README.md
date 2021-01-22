# Review App

## Development

### Install 

- `cd api`
- `vagrant up`
- ... wait
- `vagrant ssh`
- `make init`

### Test Validation

```
curl 'http://dev.symfony-range-depreaction-bug.de/order-flyer' \
  -i \
  -H 'Connection: keep-alive' \
  -H 'Pragma: no-cache' \
  -H 'Cache-Control: no-cache' \
  -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36' \
  -H 'Content-Type: text/plain;charset=UTF-8' \
  -H 'Accept: */*' \
  -H 'Origin: http://localhost:3000' \
  -H 'Referer: http://localhost:3000/' \
  -H 'Accept-Language: de' \
  --data-binary '{"quantity":1,"partnerPrice":true,"invoiceAddress":{"companyName":"CompanyName","streetName":"Teststreet","zipCode":"12345","city":"DemoCity","contactPerson":"ContactPerson","email":"test@test.com","emailRepeat":"test@test.com","telephone":"1234567890"},"addToNewsletter":false}' \
  --compressed \
  --insecure
```
