<?php
use Illuminate\Support\Facades\DB;

if ( ! function_exists('easyui_textbox')){
    function easyui_textbox($option){
        $attribute='';
        $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-textbox '.$option['class'].'"':'class="easyui-textbox"';
        $attribute.=isset($option['prompt'])?' prompt="'.$option['prompt'].'"':'';
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])&&$option['required']?' required':'';
        $attribute.=isset($option['readonly'])?' readonly':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':' style="width:197px;"';
        $attribute.=isset($option['extra'])?' '.$option['extra']:'';
        return '<input '.$attribute.' />';
    }
}
if ( ! function_exists('easyui_password')){
    function easyui_password($option){
        $attribute='';
        $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-passwordbox '.$option['class'].'"':'class="easyui-passwordbox"';
        $attribute.=isset($option['prompt'])?' prompt="'.$option['prompt'].'"':'';
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])?' required':'';
        $attribute.=isset($option['readonly'])?' readonly':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':' style="width:197px;"';
        return '<input '.$attribute.' />';
    }
}
if ( ! function_exists('easyui_email')){
    function easyui_email($option){
        $attribute='';
        $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-textbox '.$option['class'].'"':'class="easyui-textbox"';
        $attribute.=isset($option['prompt'])?' prompt="'.$option['prompt'].'"':'';
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])?' required':'';
        $attribute.=isset($option['readonly'])?' readonly':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':' style="width:197px;"';
        return '<input data-options="validType:\'email\'" '.$attribute.' />';
    }
}
if ( ! function_exists('easyui_numberbox')){
    function easyui_numberbox($option){
        $attribute='';
        $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-numberbox '.$option['class'].'"':'class="easyui-numberbox"';
        $attribute.=isset($option['prompt'])?' prompt="'.$option['prompt'].'"':'';
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])?' required':'';
        $attribute.=isset($option['readonly'])?' readonly':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':' style="width:197px;"';
        $attribute.=isset($option['extra'])?' '.$option['extra']:'';
        $attribute.=isset($option['autocomplete'])?' autocomplete="'.$option['autocomplete'].'"':'';
        return '<input data-options="min:0,precision:0,decimalSeparator:\'.\'" '.$attribute.' />';
    }
}
if ( ! function_exists('easyui_moneybox')){
    function easyui_moneybox($option){
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-numberbox '.$option['class'].'"':'class="easyui-numberbox"';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])?' required':'';
        $attribute.=isset($option['readonly'])?' readonly':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':' style="width:197px;"';
        $attribute.=isset($option['extra'])?' '.$option['extra']:'';
        return '<input '.$attribute.' data-options="min:0,precision:0,groupSeparator:\',\'" />';
    }
}
if ( ! function_exists('easyui_decimalbox')){
    function easyui_decimalbox($option){
        $attribute='';
        $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-numberbox '.$option['class'].'"':'class="easyui-numberbox"';
        $attribute.=isset($option['prompt'])?' prompt="'.$option['prompt'].'"':'';
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])?' required':'';
        $attribute.=isset($option['readonly'])?' readonly':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':' style="width:197px;"';
        $attribute.=isset($option['extra'])?' '.$option['extra']:'';
        return '<input '.$attribute.' />';
    }
}
if ( ! function_exists('easyui_numberspinner')){
    function easyui_numberspinner($option){
        $attribute='';
        $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-numberspinner '.$option['class'].'"':'class="easyui-numberspinner"';
        $attribute.=isset($option['prompt'])?' prompt="'.$option['prompt'].'"':'';
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])?' required':'';
        $attribute.=isset($option['readonly'])?' readonly':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':' style="width:80px;"';
        $attribute.=isset($option['max'])?' max='.$option['max']:'';
        $attribute.=isset($option['min'])?' min='.$option['min']:'';
        return '<input '.$attribute.' />';
    }
}
if ( ! function_exists('easyui_textarea')){
    function easyui_textarea($option){
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-textbox '.$option['class'].'"':'class="easyui-textbox"';
        $attribute.=isset($option['prompt'])?' prompt="'.$option['prompt'].'"':'';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['required'])?' required':'';
        $attribute.=isset($option['readonly'])?' readonly':'';

        if(isset($option['width'])){$width=$option['width'];}else{$width="197px";}
        if(isset($option['height'])){$height=$option['height'];}else{$height="45px";}

        $value=isset($option['value'])?$option['value']:'';
        if(isset($option['type'])&&$option['type']==2){
            return '<textarea '.$attribute.' data-options="multiline:true" style="width:197px;height:60px" />'.$value.'</textarea>';
        }else{
            return '<input '.$attribute.' class="easyui-textbox" data-options="multiline:true" style="width:'.$width.';height:'.$height.';">';
        }
    }
}
if ( ! function_exists('easyui_datebox')){
    function easyui_datebox($option){
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-datebox '.$option['class'].'"':'class="easyui-datebox"';
        $attribute.=isset($option['prompt'])?' prompt="'.$option['prompt'].'"':'';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])?' required="required"':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':'style="width:110px;"';
        return '<input '.$attribute.' data-options="formatter:myformatter,parser:myparser">';
    }
}
if ( ! function_exists('easyui_datebox')){
    function easyui_datebox($option){
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-datebox '.$option['class'].'"':'class="easyui-datebox"';
        $attribute.=isset($option['prompt'])?' prompt="'.$option['prompt'].'"':'';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])?' required="required"':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':'style="width:110px;"';
        return '<input '.$attribute.' data-options="formatter:myformatter,parser:myparser">';
    }
}
if ( ! function_exists('easyui_datebox_birth')){
    function easyui_datebox_birth($option){
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-datebox '.$option['class'].'"':'class="easyui-datebox"';
        $attribute.=isset($option['prompt'])?' prompt="'.$option['prompt'].'"':'';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])?' required="required"':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':'style="width:110px;"';
        return '<input '.$attribute.' data-options="formatter:myformatter,parser:myparser,validType:\'greaterThan[]\'">';
    }
}
if ( ! function_exists('easyui_timespinner')){
    function easyui_timespinner($option){
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-timespinner '.$option['class'].'"':'class="easyui-timespinner"';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['value'])?' value="'.$option['value'].'"':'';
        $attribute.=isset($option['required'])?' required':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':'style="width:197px;"';
        return '<input '.$attribute.' >';
    }
}
if ( ! function_exists('easyui_comboyear')){
    function easyui_comboyear($option){
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-combobox '.$option['class'].'"':'class="easyui-combobox"';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':' style="width:100px;"';
        $attribute.=isset($option['required'])?' data-options="required:true,prompt:\'---\'"':'';
        $attribute.=isset($option['readonly'])?' disabled="true"':'';

        $name=isset($option['name'])?$option['name']:'';
        $value=isset($option['value'])?$option['value']:'';

        $html='<select '.$attribute.'>
            <option value=""></option>';
        $year_=date('Y');
        for($x=$year_-20;$x<=$year_;$x++){
            if($x==$value){
                $html.="<option value='".$x."' selected>".$x."</option>";
            }else{
                $html.="<option value='".$x."'>".$x."</option>";
            }
        }
        $html.='</select>';
        return $html;
    }
}
if ( ! function_exists('easyui_combomonth')){
    function easyui_combomonth($option){
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-combobox '.$option['class'].'"':'class="easyui-combobox"';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':' style="width:100px;"';
        $attribute.=isset($option['required'])?' data-options="required:true,prompt:\'---\'"':'';
        $attribute.=isset($option['readonly'])?' disabled="true"':'';

        $name=isset($option['name'])?$option['name']:'';
        $value=isset($option['value'])?$option['value']:'';

        $html='<select '.$attribute.'>
            <option value=""></option>';
        $months=array('1'=>'Jan','2'=>'Feb','3'=>'Mar','4'=>'Apr','5'=>'May','6'=>'Jun','7'=>'Jul','8'=>'Aug','9'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
        foreach($months as $key=>$month){
            if($key==$value){
                $html.="<option value='".$key."' selected>".$month."</option>";
            }else{
                $html.="<option value='".$key."'>".$month."</option>";
            }
        }
        $html.='</select>';
        return $html;
    }
}
if ( ! function_exists('easyui_installment')){
    function easyui_installment($option){
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-combobox '.$option['class'].'"':'class="easyui-combobox"';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':' style="width:100px;"';
        $attribute.=isset($option['required'])?' data-options="required:true,prompt:\'---\'"':'';
        $attribute.=isset($option['readonly'])?' disabled="true"':'';

        $name=isset($option['name'])?$option['name']:'';
        $value=isset($option['value'])?$option['value']:'';

        $html='<select '.$attribute.'>
            <option value=""></option>';
        $numbers=[3,6,12,24,48];
        foreach($numbers as $number){
            if($number==$value){
                $html.="<option value='".$number."' selected>".$number."</option>";
            }else{
                $html.="<option value='".$number."'>".$number."</option>";
            }
        }
        $html.='</select>';
        return $html;
    }
}
if ( ! function_exists('easyui_combobox')){
    function easyui_combobox($option){
        $db = db_connect();
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-combobox '.$option['class'].'"':'class="easyui-combobox"';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['required'])?' data-options="required:true,prompt:\'---\'"':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':'style="width:197px;"';
        $attribute.=isset($option['readonly'])?' disabled="true"':'';

        $table=isset($option['table'])?$option['table']:'';
        $name=isset($option['name'])?$option['name']:'';
        $text=isset($option['text'])?$option['text']:'';
        $value=isset($option['value'])?$option['value']:'';
        $html='<select '.$attribute.'>
            <option value=""></option>';
        $query = $db->table($table)->select("$text, $value")->orderBy($text, 'ASC')->getWhere(['active' => 1]);
        foreach($query->getResultArray() as $row){
            if($value==$row[$value]){
                $html.="<option value='".$row[$value]."' selected>".$row[$text]."</option>";
            }else{
                $html.="<option value='".$row[$value]."'>".$row[$text]."</option>";
            }
        }
        $html.='</select>';
        return $html;
    }
}

if ( ! function_exists('easyui_combobox_option')){
    function easyui_combobox_option($option){
        $db = db_connect();
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-combobox '.$option['class'].'"':'class="easyui-combobox"';
        if(!isset($option['alias'])){
            $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        }else{
            $attribute.=isset($option['alias'])?' name="'.$option['alias'].'"':'';
        }
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':'style="width:197px;"';
        $attribute.=isset($option['required'])?' data-options="required:true,prompt:\'---\'"':'';
        $attribute.=isset($option['readonly'])?' disabled="true"':'';

        $label=isset($option['label'])?$option['label']:'';
        $name=isset($option['name'])?$option['name']:'';
        $value=isset($option['value'])?$option['value']:'';
        $html='<select '.$attribute.'>
            <option value=""></option>';
        $query=$db->table('options')->getWhere(['label'=>$label]);
        foreach($query->getResultArray()as $row){
            if(strtolower($value)==strtolower($row['option'])){
                $html.="<option value='".$row['option']."' selected>".$row['option']."</option>";
            }else{
                $html.="<option value='".$row['option']."'>".$row['option']."</option>";
            }
        }
        $html.='</select>';
        return $html;
    }
}
if ( ! function_exists('fivestar_rating')){
    function fivestar_rating($number){
        $html='';
        for($x=0;$x<5;$x++){
            if($x<$number){
                $html.='<span class="fa fa-star star-checked"></span>';
            }else{
                $html.='<span class="fa fa-star"></span>';
            }

        }
        return $html;
    }
}
if ( ! function_exists('clearstr')){
    function clearstr($str){
        $str=str_replace('-','_',$str);
        $str=str_replace(' ','_',$str);
        $str=str_replace('.','',$str);
        $str=str_replace("'","",$str);
        $str=str_replace("\/","",$str);
        return strtolower($str);
    }
}
if ( ! function_exists('capwords')){
    function capwords($str){
        if(!isset($str)){
            return '';
        }else{
            return ucwords(strtolower($str));
        }
    }
}
if ( ! function_exists('clearcomma')){
    function clearcomma($str){
         $str=str_replace("'"," ",$str);
        return $str;
    }
}

if ( ! function_exists('easyui_category')){
    function easyui_category($option){
        $attribute='';
        $attribute.=isset($option['id'])?'id="'.$option['id'].'"':'';
        $attribute.=isset($option['class'])?' class="easyui-combobox '.$option['class'].'"':'class="easyui-combobox"';
        $attribute.=isset($option['name'])?' name="'.$option['name'].'"':'';
        $attribute.=isset($option['multiple'])?' multiple="true"':'';
        $attribute.=isset($option['multiline'])?' multiline="true"':'';
        $attribute.=isset($option['required'])?' data-options="required:true,prompt:\'---\'"':'';
        $attribute.=isset($option['width'])?' style="width:'.$option['width'].';"':'style="width:197px;"';
        $attribute.=isset($option['readonly'])?' disabled="true"':'';

        $value=isset($option['value'])?$option['value']:'';
        $html='<select '.$attribute.'>
            <option value=""></option>';

        foreach(DB::table('categories')->get() as $row){
            if($value==$row->id){
                $html.="<option value='".$row->id."' selected>".$row->title."</option>";
            }else{
                $html.="<option value='".$row->id."'>".$row->title."</option>";
            }
        }
        $html.='</select>';
        return $html;
    }
}
