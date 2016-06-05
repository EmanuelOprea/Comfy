<?php

class Ajax_helper {
	
	public $queryparams = null;
    public $queryskip = null;
    public $querypageSize = null;
    public $querysort = null;
    public $queryfilter = null;
    
    public function __construct()
	{
        if(isset($_POST['skip']))
            $this->queryskip='OFFSET (' . $_POST['skip'] . ') ROWS';
        if(isset($_POST['take']))
            $this->querypageSize='FETCH NEXT ' . $_POST['take'] . ' ROWS ONLY';
        if(isset($_POST['sort']))
        {
            $SortArray = $_POST['sort'];
            foreach($SortArray as $param)
            {
                $this->querysort .= '[' . $param['field'] . '] ' . $param['dir'] . ', ';
            }
            $this->querysort = 'ORDER BY ' . substr($this->querysort, 0, -2);
        }
        else
            $this->querysort = 'ORDER BY 1';
        if(isset($_POST['filter']))
        {
            $where = $this->parseFilters( $_POST['filter'] );
            $this->queryfilter = $where;
        }
        else
        {
            $this->queryfilter = '1 = 1';
        }
        if($this->queryfilter)
            $this->queryparams = $this->queryfilter;
        if($this->querysort)
            $this->queryparams .= ' ' . $this->querysort;
        if($this->queryskip)
            $this->queryparams .= ' ' . $this->queryskip;
        if($this->querypageSize)
            $this->queryparams .= ' ' . $this->querypageSize;
        return $this->queryparams;
	}
    
    private function parseFilters( $filters , $count = 0 )
    { 
        $where = "";
        $intcount = 0;
        $noend= false;
        $nobegin = false;
        if( isset( $filters['filters'] ) )
        {       
            $itemcount = count( $filters['filters'] );
            if( $itemcount == 0 )
            {
                $noend= true;
                $nobegin = true;
            }
            elseif( $itemcount == 1 )
            {
                $noend= true;
                $nobegin = true;          
            }
            elseif( $itemcount > 1 )
            {
                $noend= false;
                $nobegin = false;          
            }
            foreach ( $filters['filters'] as $key => $filter )
            {
                if( isset($filter['field']))
                {
                    switch ( $filter['operator'] )
                    {
                        case 'startswith':
                            $compare = " LIKE ";
                            $field = $filter['field'];
                            $value = "'" . $filter['value'] . "%' ";
                            break;
                        case 'contains':
                            $compare = " LIKE ";
                            $field = $filter['field'];
                            $value = " '%" . $filter['value'] . "%' ";
                            break;
                        case 'doesnotcontain':
                            $compare = " NOT LIKE ";
                            $field = $filter['field'];
                            $value = " '%" . $filter['value'] . "%' ";
                            break;
                        case 'endswith':
                            $compare = " LIKE ";
                            $field = $filter['field'];
                            $value = "'%" . $filter['value'] . "' ";
                            break;
                        case 'eq':
                            $compare = " = ";
                            $field = $filter['field'];
                            $value = "'" . $filter['value'] . "'";
                            break;
                        case 'gt':
                            $compare = " > ";
                            $field = $filter['field'];
                            $value = $filter['value'];
                            break;
                        case 'lt':
                            $compare = " < ";
                            $field = $filter['field'];
                            $value = $filter['value'];
                            break;
                        case 'gte':
                            $compare = " >= ";
                            $field = $filter['field'];
                            $value = $filter['value'];
                            break;
                        case 'lte':
                            $compare = " <= ";
                            $field = $filter['field'];
                            $value = $filter['value'];
                            break;
                        case 'neq':
                            $compare = " <> ";
                            $field = $filter['field'];
                            $value = "'" . $filter['value'] . "'";
                            break;
                    };
                    if( $count == 0 && $intcount == 0 )
                    {
                        $before = "";
                        $end = " " . $filters['logic'] . " ";
                    }
                    elseif( $count > 0 && $intcount == 0 ) 
                    {
                        $before = "";
                        $end = " " . $filters['logic'] . " ";
                    }
                    else 
                    {
                        $before = " " . $filters['logic'] . " ";
                        $end = "";
                    }       
                    $where .= ( $nobegin ? "" : $before ) . $field . $compare . $value . ( $noend ? "" : $end );
                    $count ++;
                    $intcount ++;
                }
                else 
                {
                    $where .= " ( " . $this->parseFilters( $filter , $count ) . " )" ;       
                }     
                $where = str_replace( " or  or " , " or " , $where );
                $where = str_replace( " and  and " , " and " , $where );
            }
        }
        else
        {
                $where = " 1 = 1 ";
        }
        return $where;
    }
	
    function calendarfilter($startdate, $enddate = null)
    {
        if(!($request_body = file_get_contents("php://input")))
            return " 1 = 1 ";
        $filters = json_decode($request_body);
        if(!$enddate)
            return ' (['. $startdate .'] >= \'' . $filters->start . '\' AND ['. $startdate .'] < \'' . $filters->end . '\') ';
        return ' (['. $startdate .'] <= \'' . $filters->end . '\' AND ['. $enddate .'] >= \'' . $filters->start . '\') ';
    }
    
}

?>