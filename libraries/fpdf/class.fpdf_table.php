<?php
/****************************************************************************
* Software: FPDF class extention                                            *
*           Creates Pdf Tables                                              *
* Version:  3.2                                                             *
* Date:     2005/07/20                                                      *
* Author:   Bintintan Andrei  -- klodoma@ar-sd.net                          *
*                                                                           *
* Last Modification: 2006/09/25                                             *
*                                                                           *
* License:  Free for non-commercial use                                     *
*                                                                           *
* You may use and modify this software as you wish.                         *
* PLEASE REPORT ANY BUGS TO THE AUTHOR. THANK YOU   	                    *
****************************************************************************/
/**
Modifications:
2006.09.25
    - corrected a bug for empty cell texts
    - corrected a bug for cell lines aligment error on new page
2006.05.18
    - added support for cell splitting if new page ocuurs. FPDF_TABLE::Set_Table_SplitMode(true/false)
    sets the splitting mode.
            true = normal mode, the cell is splitted where the split ocuurs.
            false = if splitting is required then the cell is drawed on the new page. If the cell
            Height is bigger then the page height then the cell will be splitted.
    - if the header does not have enough space for double it's Height then a new page ocuurs.
*/
require_once('class.multicelltag.php');

//extension class
class FPDF_TABLE extends FPDF_MULTICELLTAG
{

var $tb_columns; 		//number of columns of the table
var $tb_header_type; 	//array which contains the header characteristics and texts
var $tb_header_draw = true;	//TRUE or FALSE, the header is drawed or not
var $tb_header_height;	//This is the Table Header Maximum Height
var $tb_border_draw;	//TRUE or FALSE, the table border is drawed or not
var $tb_data_type; 		//array which contains the data characteristics (only the characteristics)
var $tb_table_type; 	//array which contains the table charactersitics
var $table_startx, $table_starty;	//the X and Y position where the table starts
var $tb_split_normal;   /*  << ** special request from Marc Ulfig >>
                            = false - the split is made only if the cell width does not fit into a page height
                            = true - the split of a cell will ocuur whenever necesary
                        */
var $Draw_Header_Command;	//command which determines in the DrawData first the header draw
var $Data_On_Current_Page; // = true/false ... if on current page was some data written

//returns the width of the page in user units
function PageWidth(){
	return (int) $this->w-$this->rMargin-$this->lMargin;
}

//constructor(not a real one, but have to call it first)
//we initialize all the variables that we use
function Table_Init($col_no = 0, $header_draw = true, $border_draw = true){
	$this->tb_columns = $col_no;
	$this->tb_header_type = Array();
	$this->tb_header_draw = $header_draw;
    $this->tb_header_height = 0;
	$this->tb_border_draw = $border_draw;
	$this->tb_data_type = Array();
    $this->tb_split_normal = true;
	$this->tb_type = Array();
	$this->table_startx = $this->GetX();
	$this->table_starty = $this->GetY();

	$this->Draw_Header_Command = false; //by default we don't draw the header
	$this->Data_On_Current_Page = false;
}

//Sets the number of columns of the table
function Set_Table_Columns($nr){
	$this->tb_columns = $nr;
}

//Sets the number of columns of the table
function Set_Table_SplitMode($pSplit = true){
	$this->tb_split_normal = $pSplit;
}

/*
Characteristics constants for Header Type:
EVERY CELL FROM THE TABLE IS A MULTICELL

	WIDTH - this is the cell width. This value must be sent only to the HEADER!!!!!!!!
	T_COLOR - text color = array(r,g,b);
	T_SIZE - text size
	T_FONT - text font - font type = "Arial", "Times"
	T_ALIGN - text align - "RLCJ"
	V_ALIGN - text vertical alignment - "TMB"
	T_TYPE - text type (Bold Italic etc)
	LN_SPACE - space between lines
	BG_COLOR - background color = array(r,g,b);
	BRD_COLOR - border color = array(r,g,b);
	BRD_SIZE - border size --
	BRD_TYPE - border size -- up down, with border without!!! etc
	BRD_TYPE_NEW_PAGE - border type on new page - this is user only if specified(<>'')
	TEXT - header text -- THIS ALSO BELONGS ONLY TO THE HEADER!!!!

	all these setting conform to the settings from the multicell functions!!!!
*/

/*
Function: Set_Header_Type($type_arr) -- sets the array for the header type

type array =
	 array(
		0=>array(
				"WIDTH" => 10,
				"T_COLOR" => array(120,120,120),
				"T_SIZE" => 5,
				...
				"TEXT" => "Header text 1"
			  ),
		1=>array(
				...
			  ),
	 );
where 0,1... are the column number
*/

function Set_Header_Type($type_arr){
	$this->tb_header_type = $type_arr;
}


/*
Characteristics constants for Data Type:
EVERY CELL FROM THE TABLE IS A MULTICELL
	T_COLOR - text color = array(r,g,b);
	T_SIZE - text size
	T_FONT - text font - font type = "Arial", "Times"
	T_ALIGN - text align - "RLCJ"
	V_ALIGN - text vertical alignment - "TMB"
	T_TYPE - text type (Bold Italic etc)
	LN_SPACE - space between lines
	BG_COLOR - background color = array(r,g,b);
	BRD_COLOR - border color = array(r,g,b);
	BRD_SIZE - border size --
	BRD_TYPE - border size -- up down, with border without!!! etc
	BRD_TYPE_NEW_PAGE - border type on new page - this is user only if specified(<>'')

	all these settings conform to the settings from the multicell functions!!!!
*/

/*
Function: Set_data_Type($type_arr) -- sets the array for the header type

type array =
	 array(
		0=>array(
				"T_COLOR" => array(120,120,120),
				"T_SIZE" => 5,
				...
				"BRD_TYPE" => 1
			  ),
		1=>array(
				...
			  ),
	 );
where 0,1... are the column number
*/

function Set_Data_Type($type_arr){
	$this->tb_data_type = $type_arr;
}



/*
Function Set_Table_Type

$type_arr = array(
				"BRD_COLOR"=> array (120,120,120), //border color
				"BRD_SIZE"=>5), //border line width
				"TB_COLUMNS"=>5), //the number of columns
				"TB_ALIGN"=>"L"), //the align of the table, possible values = L, R, C equivalent to Left, Right, Center
				'L_MARGIN' => 0// left margin... reference from this->lmargin values
				)
*/
function Set_Table_Type($type_arr){

	if (isset($type_arr['TB_COLUMNS'])) $this->tb_columns = $type_arr['TB_COLUMNS'];
	if (!isset($type_arr['L_MARGIN'])) $type_arr['L_MARGIN']=0;//default values

	$this->tb_table_type = $type_arr;

}

//this functiondraws the exterior table border!!!!
function Draw_Table_Border(){
/*				"BRD_COLOR"=> array (120,120,120), //border color
				"BRD_SIZE"=>5), //border line width
				"TB_COLUMNS"=>5), //the number of columns
				"TB_ALIGN"=>"L"), //the align of the table, possible values = L, R, C equivalent to Left, Right, Center
*/

	if ( ! $this->tb_border_draw ) return;

	if ( ! $this->Data_On_Current_Page) return; //there was no data on the current page

	//set the colors
	list($r, $g, $b) = $this->tb_table_type['BRD_COLOR'];
	$this->SetDrawColor($r, $g, $b);

	//set the line width
	$this->SetLineWidth($this->tb_table_type['BRD_SIZE']);
    
    #echo $this->GetY()-$this->table_starty." ";;

	//draw the border
	$this->Rect(
		$this->table_startx,
		$this->table_starty,
		$this->Get_Table_Width(),
		$this->GetY()-$this->table_starty);

}

function End_Page_Border(){
	if (isset($this->tb_table_type['BRD_TYPE_END_PAGE'])){

		if (strpos($this->tb_table_type['BRD_TYPE_END_PAGE'], 'B') >= 0){

			//set the colors
			list($r, $g, $b) = $this->tb_table_type['BRD_COLOR'];
			$this->SetDrawColor($r, $g, $b);

			//set the line width
			$this->SetLineWidth($this->tb_table_type['BRD_SIZE']);

			//draw the line
			$this->Line($this->table_startx, $this->GetY(), $this->table_startx + $this->Get_Table_Width(), $this->GetY());
		}
	}
}

//returns the table width in user units
function Get_Table_Width()
{
	//calculate the table width
	$tb_width = 0;
	for ($i=0; $i < $this->tb_columns; $i++){
		$tb_width += $this->tb_header_type[$i]['WIDTH'];
	}
	return $tb_width;
}

//alignes the table to C, L or R(default is L)
function Table_Align(){
	//check if the table is aligned
	if (isset($this->tb_table_type['TB_ALIGN'])) $tb_align = $this->tb_table_type['TB_ALIGN']; else $tb_align='';

	//set the table align
	switch($tb_align){
		case 'C':
			$this->SetX($this->lMargin + $this->tb_table_type['L_MARGIN'] + ($this->PageWidth() - $this->Get_Table_Width())/2);
			break;
		case 'R':
			$this->SetX($this->lMargin + $this->tb_table_type['L_MARGIN'] + ($this->PageWidth() - $this->Get_Table_Width()));
			break;
		default:
			$this->SetX($this->lMargin + $this->tb_table_type['L_MARGIN']);
			break;
	}//if (isset($this->tb_table_type['TB_ALIGN'])){
}

//Draws the Header
function Draw_Header(){
	$this->Draw_Header_Command = true;
    $this->tb_header_height = 0;
}

function Init_Table_Position(){
   	$this->Table_Align();

	$this->table_startx = $this->GetX();
	$this->table_starty = $this->GetY();
}

/**   
      Draws the header line from a table.
      Call:
      @param            none
      @return           nothing
*/
function Draw_Header_(){
    
    $this->tb_header_height = 0;
    
    $this->Init_Table_Position();
    
    $this->Draw_Header_Command = false;

	//if the header will be showed
	if ( ! $this->tb_header_draw ) return;
      
    $this->DrawTableLine($this->tb_header_type, false, 1);
	
	$this->Data_On_Current_Page = true;    

}


/**   
      Draws a data line from the table.
      Call this function after the table initialization, table, header and data types are set
      Call:
      @param            $data - array containing line informations 
                        $header - header will be drawed or not in case of new page 
      @return           nothing
*/
function Draw_Data($data, $header = true){
    
    $this->DrawTableLine($data, $header, 0);   
}

/**   Draws a data or header line from the table.
      Call:
      @param            $data - array containing line informations 
                        $header - header will be drawed or not in case of new page 
                        $pDataType =    0 - normal line
                                        1 - header line
                                        2 - not implemented
      @return           nothing
*/
function DrawTableLine($data, $header, $pDataType = 0){

	$h = 0;
    $hm = 0;
	$xx = Array();
	$tt = Array();
    
    if (!$this->Data_On_Current_Page) $this->Init_Table_Position();
    
    if ($pDataType == 0){//data line
        if ($this->Draw_Header_Command){//draw the header
                $this->Draw_Header_();
	    }
    }    
         
    //maximum Height available on this page
    $AvailPageH = $this->PageBreakTrigger - $this->GetY();
    
    if ($AvailPageH <= 0){ //there is no more space on this page
        $this->TableAddPage($header);//add a page without header
        $this->DrawTableLine($data, $header, $pDataType);//recall this function
        return;//exit this function
    }

        
    
    $MaxPageH = $this->PageBreakTrigger - $this->tMargin;
    $split = false;
    
    $backdata = $data; //backup data in case of split or recall;
    
	//calculate the maximum height of the cells
	for($i=0; $i < $this->tb_columns; $i++)
	{
        if (!isset($data[$i]['TEXT']) || ($data[$i]['TEXT']=='')) $data[$i]['TEXT'] = " ";
		if (!isset($data[$i]['T_FONT'])) $data[$i]['T_FONT'] = $this->tb_data_type[$i]['T_FONT'];
		if (!isset($data[$i]['T_TYPE'])) $data[$i]['T_TYPE'] = $this->tb_data_type[$i]['T_TYPE'];
		if (!isset($data[$i]['T_SIZE'])) $data[$i]['T_SIZE'] = $this->tb_data_type[$i]['T_SIZE'];
		if (!isset($data[$i]['T_COLOR'])) $data[$i]['T_COLOR'] = $this->tb_data_type[$i]['T_COLOR'];
		if (!isset($data[$i]['T_ALIGN'])) $data[$i]['T_ALIGN'] = $this->tb_data_type[$i]['T_ALIGN'];
		if (!isset($data[$i]['V_ALIGN'])) $data[$i]['V_ALIGN'] = $this->tb_data_type[$i]['V_ALIGN'];
		if (!isset($data[$i]['LN_SIZE'])) $data[$i]['LN_SIZE'] = $this->tb_data_type[$i]['LN_SIZE'];
		if (!isset($data[$i]['BRD_SIZE'])) $data[$i]['BRD_SIZE'] = $this->tb_data_type[$i]['BRD_SIZE'];
		if (!isset($data[$i]['BRD_COLOR'])) $data[$i]['BRD_COLOR'] = $this->tb_data_type[$i]['BRD_COLOR'];
		if (!isset($data[$i]['BRD_TYPE'])) $data[$i]['BRD_TYPE'] = $this->tb_data_type[$i]['BRD_TYPE'];
		if (!isset($data[$i]['BG_COLOR'])) $data[$i]['BG_COLOR'] = $this->tb_data_type[$i]['BG_COLOR'];

		$this->SetFont(	$data[$i]['T_FONT'],
						$data[$i]['T_TYPE'],
						$data[$i]['T_SIZE']);

		$data[$i]['CELL_WIDTH'] = $this->tb_header_type[$i]['WIDTH'];

		if (isset($data[$i]['COLSPAN'])){

			$colspan = (int) $data[$i]['COLSPAN'];//convert to integer

			for ($j = 1; $j < $colspan; $j++){
				//if there is a colspan, then calculate the number of lines also with the with of the next cell
				if (($i + $j) < $this->tb_columns)
					$data[$i]['CELL_WIDTH'] += $this->tb_header_type[$i + $j]['WIDTH'];
			}
		}
       
        $MaxLines = floor($AvailPageH / $data[$i]['LN_SIZE']);//floor this value, must be the lowest possible
        if (!isset($data[$i]['TEXT_STRLINES'])) $data[$i]['TEXT_STRLINES'] = $this->StringToLines($data[$i]['CELL_WIDTH'], $data[$i]['TEXT']);
        $NoLines = count($data[$i]['TEXT_STRLINES']);
        
        $hm = max($hm, $data[$i]['LN_SIZE'] * $NoLines);//this would be the normal height
        
        if ($NoLines > $MaxLines){
            $split = true;
            $NoLines = $MaxLines;
            $data[$i]['TEXT_SPLITLINES'] = array_splice($data[$i]['TEXT_STRLINES'], $MaxLines);
        }      
               
        $data[$i]['CELL_LINES'] = $NoLines;

		//this is the maximum cell height
		$h = max($h, $data[$i]['LN_SIZE'] * $data[$i]['CELL_LINES']);

		if (isset($data[$i]['COLSPAN'])){
			//just skip the other cells
			$i = $i + $colspan - 1;
		}       
	}


    if ($pDataType == 0){//data line        
    
        if (!$this->tb_split_normal){//split only if the cell height is bigger than a page size        
        
            if ($split && (($hm + $this->tb_header_height) < $MaxPageH)){//if the header is splitted and it has space on a page
                $this->TableAddPage($header);//add a page without header
                $this->DrawTableLine($backdata, $header, 0);//recall this function
                return;
            }
            
        }
        
    }    
    
       
    if ($pDataType == 1){//header line
        $this->tb_header_height = $hm;

        if ($split && ($hm < $MaxPageH)){//if the header is splitted and it has space on a page
            //new page
            $this->TableAddPage(false);//add a page without header
            $this->DrawTableLine($backdata, $header, 1);//recall this function
            return;            
        }
        
        if ( ((2*$h) > $AvailPageH) && ((2*$h) < $MaxPageH)){
        /*
            if the header double size is bigger then the available size 
            but the double size is smaller than a page size
            >>> we draw a new page
        **/
            $this->TableAddPage(false);//add a page withot header
            $this->DrawTableLine($backdata, $header, 1);//recall this function
            return;
        }
    }
    
	$this->Table_Align();

	//Draw the cells of the row
	for( $i = 0; $i < $this->tb_columns; $i++ )
	{
		//border size BRD_SIZE
		$this->SetLineWidth($data[$i]['BRD_SIZE']);

		//fill color = BG_COLOR
		list($r, $g, $b) = $data[$i]['BG_COLOR'];
		$this->SetFillColor($r, $g, $b);

		//Draw Color = BRD_COLOR
		list($r, $g, $b) = $data[$i]['BRD_COLOR'];
		$this->SetDrawColor($r, $g, $b);

		//Text Color = T_COLOR
		list($r, $g, $b) = $data[$i]['T_COLOR'];
		$this->SetTextColor($r, $g, $b);

		//Set the font, font type and size
		$this->SetFont(	$data[$i]['T_FONT'],
						$data[$i]['T_TYPE'],
						$data[$i]['T_SIZE']);

		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();

		//print the text
		$this->MultiCellTable(
				$data[$i]['CELL_WIDTH'],
				$data[$i]['LN_SIZE'],
				$data[$i]['TEXT_STRLINES'],
				$data[$i]['BRD_TYPE'],
				$data[$i]['T_ALIGN'],
				$data[$i]['V_ALIGN'],
				1,
				$h - $data[$i]['LN_SIZE'] * $data[$i]['CELL_LINES']
				);

		//Put the position to the right of the cell
		$this->SetXY($x + $data[$i]['CELL_WIDTH'],$y);

		//if we have colspan, just ignore the next cells
		if (isset($data[$i]['COLSPAN'])){
			$i = $i + (int)$data[$i]['COLSPAN'] - 1;
		}

	}

	$this->Data_On_Current_Page = true;

	//Go to the next line
	$this->Ln($h);
    
    if ($split){
        
	    //calculate the maximum height of the cells
	    for($i=0; $i < $this->tb_columns; $i++){
            $backdata[$i]['TEXT_STRLINES'] = isset($data[$i]['TEXT_SPLITLINES']) ? $data[$i]['TEXT_SPLITLINES'] : array();
        }
        
        $this->TableAddPage($header);//we have a page split, add a page

        if ($pDataType == 1) $header = false;
        $this->DrawTableLine($backdata, $header, $pDataType);
    }
}//DrawTableLine

/**   Adds a new page in the pdf document and initializes the table and the header if necessary.
      Call:
      @param
                        $header - boolean - if the header is drawed or not
      @return           nothing
*/
function TableAddPage($header = true){
    $this->Draw_Table_Border();//draw the table border

    $this->End_Page_Border();//if there is a special handling for end page??? this is specific for me

    $this->AddPage($this->CurOrientation);//add a new page

    $this->Data_On_Current_Page = false;

    $this->table_startx = $this->GetX();
    $this->table_starty = $this->GetY();
    if ($header) $this ->Draw_Header();//if we have to draw the header!!!    
}//TableAddPage

/**   This method allows printing text with line breaks.
      It works like a modified MultiCell
      Call:
      @param
                        $w - width
                        $h - line height
                        $txtData - the outputed text
                        $border - border(LRTB 0 or 1)
                        $align - horizontal align 'JLR'
                        $valign - Vertical Alignment - Top, Middle, Bottom
                        $fill - fill (1/0)
                        $vh - vertical adjustment - the Multicell Height will be with this VH Higher!!!!
      @return           nothing
*/
function MultiCellTable($w, $h, $txtData, $border=0, $align='J', $valign='T', $fill=0, $vh=0)
{
	$b1 = '';//border for top cell
	$b2 = '';//border for middle cell
	$b3 = '';//border for bottom cell

	if($border)
	{
		if($border==1)
		{
			$border = 'LTRB';
			$b1 = 'LRT';//without the bottom
			$b2 = 'LR';//without the top and bottom
			$b3 = 'LRB';//without the top
		}
		else
		{
			$b2='';
			if(is_int(strpos($border,'L')))
				$b2.='L';
			if(is_int(strpos($border,'R')))
				$b2.='R';
			$b1=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
			$b3=is_int(strpos($border,'B')) ? $b2.'B' : $b2;

		}
	}

	switch ($valign){
		case 'T':
			$wh_T = 0;//Top width
			$wh_B = $vh - $wh_T;//Bottom width
			break;
		case 'M':
			$wh_T = $vh/2;
			$wh_B = $vh/2;
			break;
		case 'B':
			$wh_T = $vh;
			$wh_B = 0;
			break;
		default://default is TOP ALIGN
			$wh_T = 0;//Top width
			$wh_B = $vh - $wh_T;//Bottom width
	}

	//save the X position
	$x = $this->x;
	/*
		if $wh_T == 0 that means that we have no vertical adjustments so I will skip the cells that
		draws the top and bottom borders
	*/

	if ($wh_T != 0)//only when there is a difference
	{
		//draw the top borders!!!
		$this->Cell($w,$wh_T,'',$b1,2,$align,$fill);
	}

	$b2 = is_int(strpos($border,'T')) && ($wh_T == 0) ? $b2.'T' : $b2;
	$b2 = is_int(strpos($border,'B')) && ($wh_B == 0) ? $b2.'B' : $b2;

	//$this->MultiCell($w,$h,$txt,$b2,$align,$fill);
	$this->MultiCellTag($w, $h, $txtData, $b2, $align, 1, false);

	if ($wh_B != 0){//only when there is a difference

		//go to the saved X position
		//a multicell always runs to the begin of line
		$this->x = $x;

		$this->Cell($w, $wh_B, '', $b3, 2, $align,$fill);

		$this->x=$this->lMargin;
	}

}


function ImageEps ($file, $x, $y, $w=0, $h=0, $link='', $useBoundingBox=true){
    $data = file_get_contents($file);
    if ($data===false) $this->Error('EPS file not found: '.$file);

    # strip binary bytes in front of PS-header
    $start = strpos($data, '%!PS-Adobe');
    if ($start>0) $data = substr($data, $start);

    # find BoundingBox params
    ereg ("%%BoundingBox:([^\r\n]+)", $data, $regs);
    if (count($regs)>1){
        list($x1,$y1,$x2,$y2) = explode(' ', trim($regs[1]));
    }
    else $this->Error('No BoundingBox found in EPS file: '.$file);

    $start = strpos($data, '%%EndSetup');
    if ($start===false) $start = strpos($data, '%%EndProlog');
    if ($start===false) $start = strpos($data, '%%BoundingBox');

    $data = substr($data, $start);

    $end = strpos($data, '%%PageTrailer');
    if ($end===false) $end = strpos($data, 'showpage');
    if ($end) $data = substr($data, 0, $end);

    # save the current graphic state
    $this->_out('q');

    $k = $this->k;

    if ($useBoundingBox){
        $dx = $x*$k-$x1;
        $dy = $y*$k-$y1;
    }else{
        $dx = $x*$k;
        $dy = $y*$k;
    }
    
    # translate
    $this->_out(sprintf('%.3f %.3f %.3f %.3f %.3f %.3f cm', 1,0,0,1,$dx,$dy+($this->hPt - 2*$y*$k - ($y2-$y1))));
    
    if ($w>0){
        $scale_x = $w/(($x2-$x1)/$k);
        if ($h>0){
            $scale_y = $h/(($y2-$y1)/$k);
        }else{
            $scale_y = $scale_x;
            $h = ($y2-$y1)/$k * $scale_y;
        }
    }else{
        if ($h>0){
            $scale_y = $h/(($y2-$y1)/$k);
            $scale_x = $scale_y;
            $w = ($x2-$x1)/$k * $scale_x;
        }else{
            $w = ($x2-$x1)/$k;
            $h = ($y2-$y1)/$k;
        }
    }
    
    # scale    
    if (isset($scale_x))
        $this->_out(sprintf('%.3f %.3f %.3f %.3f %.3f %.3f cm', $scale_x,0,0,$scale_y, $x1*(1-$scale_x), $y2*(1-$scale_y)));
    
    # handle pc/unix/mac line endings
    $lines = split ("\r\n|[\r\n]", $data);

    $u=0;
    $cnt = count($lines);
    for ($i=0;$i<$cnt;$i++){
        $line = $lines[$i];
        if ($line=='' || $line{0}=='%') continue;
        $len = strlen($line);
        if ($len>2 && $line{$len-2}!=' ') continue;
        $cmd = $line{$len-1};

        switch ($cmd){
            case 'm':
            case 'l':
            case 'v':
            case 'y':
            case 'c':

            case 'k':
            case 'K':
            case 'g':
            case 'G':

            case 's':
            case 'S':

            case 'J':
            case 'j':
            case 'w':
            case 'M':
            case 'd' :
            
            case 'n' :
            case 'v' :
                $this->_out($line);
                break;
                                        
            case 'x': # custom colors
                list($c,$m,$y,$k) = explode(' ', $line);
                $this->_out("$c $m $y $k k");
                break;
                
            case 'Y':
                $line{$len-1}='y';
                $this->_out($line);
                break;

            case 'N':
                $line{$len-1}='n';
                $this->_out($line);
                break;
        
            case 'V':
                $line{$len-1}='v';
                $this->_out($line);
                break;
                                                
            case 'L':
                $line{$len-1}='l';
                $this->_out($line);
                break;

            case 'C':
                $line{$len-1}='c';
                $this->_out($line);
                break;

            case 'b':
            case 'B':
                $this->_out($cmd . '*');
                break;

            case 'f':
            case 'F':
                if ($u>0){
                    $isU = false;
                    $max = min($i+5,$cnt);
                    for ($j=$i+1;$j<$max;$j++)
                        $isU = ($isU || ($lines[$j]=='U' || $lines[$j]=='*U'));
                    if ($isU) $this->_out("f*");
                }else
                    $this->_out("f*");
                break;

            case 'u':
                if ($line{0}=='*') $u++;
                break;

            case 'U':
                if ($line{0}=='*') $u--;
                break;
            
            #default: echo "$cmd<br>"; #just for debugging
        }

    }

    # restore previous graphic state
    $this->_out('Q');
    if ($link)
        $this->Link($x,$y,$w,$h,$link);
}

function Header($yTextHeader=0) {
	$this->SetFillColor(255,255,255);
	$this->SetTextColor(70, 110, 165);
	$this->SetXY(3,3);
    $this->Cell(204,24,'',1,1,'L',1);
	if(is_file('../ui/images/logo_'.$_SESSION['societe'].'.jpg')) {
	    $this->Image('../ui/images/logo_'.$_SESSION['societe'].'.jpg',4,8,0,14);
	}
	$this->SetFont('Arial','',12);
	$this->SetXY(70,8);
	$this->MultiCell(0, 9, $_SESSION['titre'], 0, 'C', 0);
	$this->SetXY(0,35);
}

function Footer() {
	if(is_file('../ui/images/footer_'.$_SESSION['societe'].'.jpg')) {
	    $this->Image('../ui/images/footer_'.$_SESSION['societe'].'.jpg',4,285,204);
	}
}

function AddPage($orientation='')
{
	//Start a new page
	if($this->state==0)
		$this->Open();
	$family=$this->FontFamily;
	$style=$this->FontStyle.($this->underline ? 'U' : '');
	$size=$this->FontSizePt;
	$lw=$this->LineWidth;
	$dc=$this->DrawColor;
	$fc=$this->FillColor;
	$tc=$this->TextColor;
	$cf=$this->ColorFlag;
	if($this->page>0)
	{
		//Page footer
		$this->InFooter=true;
		$this->Footer();
		$this->InFooter=false;
		//Close page
		$this->_endpage();
	}
	//Start new page
	$this->_beginpage($orientation);
	//Set line cap style to square
	$this->_out('2 J');
	//Set line width
	$this->LineWidth=$lw;
	$this->_out(sprintf('%.2f w',$lw*$this->k));
	//Set font
	if($family)
		$this->SetFont($family,$style,$size);
	//Set colors
	$this->DrawColor=$dc;
	if($dc!='0 G')
		$this->_out($dc);
	$this->FillColor=$fc;
	if($fc!='0 g')
		$this->_out($fc);
	$this->TextColor=$tc;
	$this->ColorFlag=$cf;
	//Page header
	if($this->tb_header_draw)
		$this->Header($yTextHeader);
	//Restore line width
	if($this->LineWidth!=$lw)
	{
		$this->LineWidth=$lw;
		$this->_out(sprintf('%.2f w',$lw*$this->k));
	}
	//Restore font
	if($family)
		$this->SetFont($family,$style,$size);
	//Restore colors
	if($this->DrawColor!=$dc)
	{
		$this->DrawColor=$dc;
		$this->_out($dc);
	}
	if($this->FillColor!=$fc)
	{
		$this->FillColor=$fc;
		$this->_out($fc);
	}
	$this->TextColor=$tc;
	$this->ColorFlag=$cf;
}

function NbLines($w, $txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r", '', $txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
}//end of pdf_table class

?>