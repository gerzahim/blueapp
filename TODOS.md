# TO DO List

## TO DO


- [ ] Search List (autosearch with axios)
- [ ] Report Filters

## Bugs

- [ ] created_at Stock (add created_at and updated_at in Model), update StockController.php
- [ ] Send refurbishment ( Create Order to Customer with refurbishes Items)

## Core Improvements
- [ ] Create Detail error.message on Products Row Lines
- [ ] Refactor PaddingStringsTrait , Replace - by spaces
- [ ] Add Vue Datatables to RMA
      https://vuejsexamples.com/data-table-simplify-datatable-component-for-vue-2-x/
      https://codepen.io/ratiw/pen/GmJayw
      https://vuejsexamples.com/a-vue-js-datatable-component-for-laravel-that-works-with-bootstrap/
      https://github.com/jamesdordoy/laravel-vue-datatable
- [ ] holding ( from Create Order Select Holding, List Holding [Show Order][complete order][revert Order])

- [ ] style Stock
  <td style="font-weight: bold; text-align:right;background-color:red; color:white">+11 </td>
  <td style="font-weight: bold; text-align:right;background-color:#FFEEAA;"> +1,192 </td>

- [ ] If fail refill values ( No priority) on Vue component

## Crazy Ideas :

- [ ] Turbolinks

## Deploy with Cpanel and winSCP

- update/replace src/public (file by file) avoid .htaccess
- update/replace src/resources

- update/replace src/app
- update/replace src/routes

- descomprim node_modules
- descomprim vendor

- any new variable on .ENV
- import .sql


## RUN

- Run Compose Install / compose update
- Run Migrations
- Npm Install

user1@gmail.com/12345678
user1@gmail.com/password

## Done :checkered_flag:

- [X] https://oakml.com/auto-logout-inactive-users-after-a-period-of-time-in-laravel/
- [X] Fix Order Component
- [X] Fix RMAComponent
- [X] Fix RefurbishComponent
- [X] Fix Inventory PO First Colum Order DESC PO , Order PO ASC
- [X] Disable Search from TOP 
- [X] Fix Icons on All places
- [x] Define core ASF style & header information in the model and
      implement in various applications. [lib/whimsy/asf/themes.rb](lib/whimsy/asf/themes.rb)
- [x] capital letter only works on Chrome
- [x] 1565ewewe when create batch pass lowercase to list
- [x] Refactor Controller PO\*
- [x] Refactor Controller Order
- [x] ResponseController new Class get_rmas pending for move
- [x] loading gif, PO\*
- [x] messages new Trait, PO\*
- [x] rules new Trait, PO\*
- [x] Data Trait for Insert, PO\*

- [x] messages new Trait, Order
- [x] rules new Trait, Order
- [x] Data Trait for Insert, Order
- [x] Refactor, One Component PO
- [x] Refactor Controller RMA
- [x] Refactor Controller Refurbishment

- [x] fix bug in Order when try to insert more than available
- [x] fix bug in Order when update the order instead update do insert
- [x] remove Reset Button RMA, replace for Cancel Button
- [x] remove Reset Button , Refurbishment, replace for Cancel Button  

- [x] loading gif, Order
- [x] Refactor, One Component ORDER
- [x] remove Reset Button ORDER

- [x] messages new Trait, RMA
- [x] rules new Trait, PO\*, RMA
- [x] Data Trait for Insert, RMA
- [x] Refactor, One Component RMA
- [x] loading gif, RMA
- [x] remove Reset Button RMA, replace for Cancel Button
- [x] fix available RMA
      if Customer
      take qty Ordered
      if Vendor
      take qty available
- [x] show PO
- [x] show Order
- [x] show RMA
- [x] add icons to Buttons
- [x] Create Modal New Product
- [x] add Line
  - [x] validate vars is ok with values and not duplicate
  - [x] Create computed vars to validate no duplication
- [x] Clean Code PO
- [x] List errors.vars

- [x] Create Modal New Category
- [x] Create Modal New Product Dimension
- [x] Fix add Unique Category from Controler and Modal
- [X] add instant PO Name Checker 
- [X] Add New Product by Create New
