<?php
/**
 |------------------------------------------------
 | Custom Global Helper
 |------------------------------------------------
 */

/**
 *  Detect Active Navigation item vie Route Name
 *  
 *  @param string $route_name
 *  @param string $class
 *  @return string|void
 */
if (!function_exists('isActive')) {
    
    function isActive($route_name, $class="active")
    {
        if (Request::is($route_name) or Route::currentRouteName() == $route_name) {
            return $class;           
        }
        
        return;
    }
}

if (!function_exists('isRouteExist')) {
    
    function isRouteExist($route_name)
    {
        return Route::has($route_name);
    }
}

if (!function_exists('getRouteMethod')) {
    
    function getRouteMethod($route_name)
    {
        $getRoute = Route::getRoutes();
        
        if ($getRoute->hasNamedRoute($route_name)) {
            return ' <span class="badge">'.implode(' | ',$getRoute->getByName($route_name)->methods()).'</span>';
        }
        
        return '<span class="badge badge-warning">Not Found</span>';
    }
}

if (!function_exists('getRouteNameList')) {
    
    function getRouteNameList(array $except=[])
    {
        $except = array_merge($except, ['login', 'logout', 'password.request', 'password.email', 'password.reset', 'test']);
        
        $routeList = [];
        foreach(Route::getRoutes() as $value) {
            if ($value->getName()) $routeList[$value->getName()] = $value->getName();
        }          
        $obj = collect($routeList);
        
        return $obj->except($except)->toArray();
    }

}

if (!function_exists('can')) {
    
    function can($route_name)
    {
        return !auth()->user()->can($route_name) ? 'hideAction' : '';
    }
}

if (!function_exists('reference_no')) {
    
    function reference_no($id)
    {
        return date('Y') . str_pad($id, 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('generateToken')) {
    
    function generateToken($data, $pad=4)
    {
        $token = '';
        if (is_array($data)) {
            if (count($data) > 0) {
                $token = implode('', $data);
            }
        } else {
            $token = $data;
        }
        
        return strtolower(sha1($token) . str_random($pad));
    }
}

if (!function_exists('trimToken')) {
    
    function trimToken($token, $pad=4)
    {
        return substr($token, 0, -($pad));
    }
}


if (!function_exists('dropdownList')) {
    
    function dropdownList($model, array $col=[])
    {
        $rows = [];        
        
        foreach ($model as $row) {
            $key = isset($col[0]) ? $row->{$col[0]} : $row->id;
            $val = isset($col[1]) ? $row->{$col[1]} : $row->name;
            $rows[$key] = $val;  
        } 
        
        return $rows;  
    }
}

if (!function_exists('diffInDays')) {
    
    function diffInDays($userDate, $status) 
    {    
        $userDate = Carbon\Carbon::parse($userDate);
        
        $dateNow = Carbon\Carbon::now();
        
        if ($dateNow->lt($userDate) and $status==0) {
            
            return true;
        }       
        
        return false;
    }
}

if (!function_exists('dtColumn')) {
    
    function dtColumn($columns=[]) 
    {    
        $data = [];
        if (count($columns) > 0) {            
            foreach($columns as $column) {
                $data[] = ['data' => $column];
            }            
        }
        
        return json_encode($data);
    }
}

if (!function_exists('dateFormat')) {
    
    function dateFormat($datetime=false, $type=false) 
    {    
        switch( $type )
        {
            case 1: $type = 'Y-m-d';        break;
            case 2: $type = 'Ymd';          break;
            case 3: $type = 'F j, Y';       break;
            case 4: $type = 'F j, Y g:iA';  break;
            case 5: $type = 'F j, Y g:i:sA';break;
            case 6: $type = 'M-d-y';        break;
            case 7: $type = 'M-d-y g:iA';   break;
            case 8: $type = 'M-d-y g:i:sA'; break;
            case 9: $type = 'g:iA';         break;
            case 10: $type = 'g:i:sA';      break;
            case 11: $type = 'F j, Y, g:i a'; break;
            case 12: $type = 'D, d-M-y @ g:i a'; break;
            case 13: $type = 'm/d/Y g:i A'; break;
            
            case 14: $type = 'm/d/Y'; break;
            case 15: $type = 'g:ia'; break;
            case 16: $type = 'g:ia'; break;
            
            default: $type = 'Y-m-d H:i:s';
        }
        $result = date($type);
        
        if( $datetime )
            $result = date($type, strtotime($datetime));
            
        return $result;
    }
}

if (!function_exists('getKey')) {
    
    function getKey($search, $array) 
    {    
        return array_search($search, array_values($array));
    }
}
