```js
    auth\(\)->user\(\)->permission->[a-zA-Z]+\[[^\]]*\] == '[0-9]+'
```

auth\(\)->user\(\)->permission->[a-zA-Z]+\['([^']*)'\] == '[0-9]+'

auth\(\)->user\(\)->permission->[a-zA-Z]+\['([^']*)'\]\s+==\s+[0-9]+

auth()->user()->can('$1')


auth\(\)->user\(\)->can\('([^']*)'\)\s*\)\s*\{\s*\n*\s*abort

`!auth()->user()->can('$1') ) {
            abort`


 !\s*auth\(\)->user\(\)->can\('([^']*)'\)\s*\)\s*\{\s*\n*\s*abort
 !auth()->user()->can('$1') ) {
            abort
			

\(\s*auth\(\)->user\(\)->can\('([^']*)'\)\s*\)\s*\{\s*\n*\s*abort
(!auth()->user()->can('$1') ) {
            abort
			
\(\s*auth\(\)->user\(\)->can\('([^']*)'\)\s*\)\s*\{\s*\n*\s*abort
(!auth()->user()->can('$1') ) {
            abort

			
			
\$role->permission->[a-z_A-Z]+\['([^']*)'\]\s*==\s*'1'
$role->hasPermissionTo('$1')




\$userPermission->[a-zA-Z]+\['([^']*)'\]\s*==\s*'[0-9]+'\s*
auth()->user()->can('$1')



New (From GenuinePOS): 

isset\(auth\(\)->user\(\)->permission->[a-zA-Z_\-0-9]+\['([^']*)'\]\)
auth()->user()->can('$1')

