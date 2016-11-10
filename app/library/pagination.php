<?php

/**
 * Pagination
 * Version: 1.0.0
 * Author: Dinh Phong
 * Description: Simple pagination in PHP
 */

namespace Library;

class Pagination {
	protected $start;
	protected $end;
	protected $last;
	protected $page;
	protected $total;
	protected $limit;
	protected $get;
	protected $redirect_url;

	/**
	 * Construct value
	 */
	public function __construct($limit,$total,$args = 5){
		$this->page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$this->total = ($total == 0) ? 1 : $total;
		$this->limit = $limit;
		$this->last = ceil($this->total/$this->limit);
		$this->start = ($this->page-$args < 1) ? 1 : ($this->page-$args);
		$this->end = ($this->page+$args>$this->last) ? $this->last : ($this->page+$args);
	}

	/**
	 * Echo pagination
	 */
	public function pagination(){
		if($this->total > $this->limit) {
			$this->redirect_url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '';
			if(isset($_GET) && isset($_GET['page'])) {
				unset($_GET['page']);
			}

			foreach($_GET as $k => $v){
				$this->get .= '&'.$k.'='.$v;
			}

			$ret = '<ul class="pagination">';
	        $ret .= '<li class="';
	        if($this->page == 1):
	        	$ret .= 'disabled';
	       	endif;
	       	$ret .= '"><a href="'.$this->redirect_url.'?page=1'.$this->get.'">Bắt đầu</a></li>';
	        $ret .= '<li class="';
	        if($this->page == 1):
	        	$ret .= 'disabled';
	        endif;
	        $ret .= '"><a href="'.$this->redirect_url.'?page='.($this->page-1).$this->get.'"><span aria-hidden="true">&laquo;</span></a></li>';
	        for($i = $this->start; $i <= $this->end; $i++):
	        	$ret .= '<li class="';
	        	if($i==$this->page):
	        		$ret.='active';
	        	endif;
	        	$ret .= '"><a href="'.$this->redirect_url.'?page='.$i.$this->get.'">'.$i.'</a></li>';
	        endfor;
	        $ret .= '<li class="';
	        if ($this->page == $this->last):
	        	$ret.='disabled';
	        endif;
	        	$ret.='"><a href="'.$this->redirect_url.'?page='.($this->page+1).$this->get.'"><span aria-hidden="true">&raquo;</span></a></li>';
	            $ret.='<li class="';
	            if ($this->page == $this->last):
	            	$ret.='disabled';
	        	endif;
	        	$ret.='"><a href="'.$this->redirect_url.'?page='.$this->last.$this->get.'">Kết thúc</a></li>';
	        $ret .= '</ul>';
			echo $ret;

			echo "<script>
			$(document).ready(function(){
	            $('ul.pagination li.disabled a, ul.pagination li.active a').click(function(e){
	                e.preventDefault();
	            });
	        })
	        </script>";
			return true;
		}
	}
}