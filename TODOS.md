# TO DO List 

## TO DO   
- [ ] show PO
- [ ] show Order
- [ ] show RMA  
- [ ] Send refurbishment on ( Create Order )
- [ ] Report Filters
- [ ] Import on Server GoDaddy

## Bugs 
     
- [ ] created_at Stock (add created_at and updated_at in Model), update StockController.php
- [ ] check rightjoin by join on ajaxcall ResponseController

## Core Improvements
- [ ] Refactor PaddingStringsTrait , Replace - by spaces
- [ ] 419 Page Expired , handle redirect or message error
- [ ] Add Vue Datatables to RMA
      https://vuejsexamples.com/data-table-simplify-datatable-component-for-vue-2-x/
      https://codepen.io/ratiw/pen/GmJayw
      https://vuejsexamples.com/a-vue-js-datatable-component-for-laravel-that-works-with-bootstrap/
      https://github.com/jamesdordoy/laravel-vue-datatable
      
- [ ] holding ( from Create Order Select Holding, List Holding [Show Order][Complete Order][revert Order]) 

- [ ] style Stock
    <td style="font-weight: bold; text-align:right;background-color:red; color:white">+11 </td>
    <td style="font-weight: bold; text-align:right;background-color:#FFEEAA;"> +1,192 </td>
          
- [ ] If fail refill values ( No priority) on Vue component
- [ ] Disable Button Save or Message why not submit

## Crazy Ideas :

- [ ] Turbolinks   

  
## Deploy with Cpanel
- update/replace src/app
- update/replace src/public (file by file)
- update/replace src/resources
- update/replace src/routes

- descomprim node_modules
- descomprim vendor

- any new variable on .ENV
- import .sql 

## For check 
https://docs.laravel-enso.com/



## RUN
- Run Compose Install / compose update
- Run Migrations 
- Npm Install 

user1@gmail.com/12345678


## Done :checkered_flag:

- [x] Define core ASF style & header information in the model and 
      implement in various applications.  [lib/whimsy/asf/themes.rb](lib/whimsy/asf/themes.rb)
- [x] capital letter only works on Chrome
- [x] 1565ewewe when create batch pass lowercase to list
- [x] Refactor Controller PO*
- [X] Refactor Controller Order  
- [x] ResponseController new Class  get_rmas pending for move
- [X] loading gif, PO*
- [X] messages new Trait, PO*
- [X] rules new Trait, PO*
- [X] Data Trait for Insert, PO*

- [X] messages new Trait, Order
- [X] rules new Trait, Order
- [X] Data Trait for Insert, Order
- [X] Refactor, One Component PO
- [X] Refactor Controller RMA
- [X] Refactor Controller Refurbishment

- [X] fix bug in Order when try to insert more than available
- [X] fix bug in Order when update the order instead update do insert
- [X] remove Reset Button RMA, replace for Cancel Button
- [x] remove Reset Button , Refurbishment, replace for Cancel Button           
      
- [x] loading gif, Order
- [X] Refactor, One Component ORDER
- [x] remove Reset Button ORDER

- [X] messages new Trait, RMA
- [x] rules new Trait, PO*, RMA
- [x] Data Trait for Insert, RMA
- [X] Refactor, One Component RMA
- [x] loading gif, RMA
- [x] remove Reset Button RMA, replace for Cancel Button
- [X] fix available RMA 
     if Customer
        take qty Ordered 
     if Vendor 
        take qty available