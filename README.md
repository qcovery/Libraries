# Libraries
This module adds a custom library selector for the sidebar and the option to expand the search scope.

## Usage
Integrate the module in the `modules` directory of VuFind and activate it by adding `Libraries` to `VUFIND_LOCAL_MODULES`.
When adding the module manually make sure to copy and adapt the config file and copy/symlink the theme. 

Add the following lines to your templates to enable the functionality of this module:
```php
<?=$this->render('search/libraries-results.phtml'); ?>
```
Add this line to the result lists sidebar.

```php
<?=$this->render('RecordDriver/SolrDefault/libraries-core.phtml', ['searchClassId' => $this->searchClassId, 'driver' => $this->driver]) ?>
```
Add this line to the detail view.

```php
<?=$this->render('RecordDriver/SolrDefault/libraries-result-list.phtml', ['searchClassId' => $this->searchClassId, 'driver' => $this->driver]) ?>
```
Add this line to the body of the result list entry.

```php
<?=$this->render('search/libraries-home.phtml', ['searchClassId' => $tab['id']]); ?>
```
Add this line to your home page.