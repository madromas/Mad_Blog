<?php

class qa_html_theme_layer extends qa_html_theme_base {
  

    function q_list_items($q_items) 
    {
        $template = qa_request() == '' ? 'home' : qa_request_part(0);
		
			
        if (qa_opt('king_enable_adverts')) {
        
		if ( $this->template == 'question' ) {
			foreach ($q_items as $q_item)
			    $this->king_related($q_item);	
				
		}		
		else {
            
                $this->output('<DIV id="container">', ''); 

                $count=1; 
                foreach ($q_items as $q_item) { 
                    $this->q_list_item($q_item); 
                    if ($count%qa_opt('king_after_every') == 0) {
                        
                        $link = qa_opt('king_advert_destination_link');
                        
                        $this->output('<div class="box king-q-list-item">');
                        
                        if (qa_opt('king_google_adsense')) {
                            
                            $this->output(qa_opt('king_google_adsense_codebox'));
                            
                        } elseif (qa_opt('king_image_advert')) {                            

                            $this->output('<a href="'.qa_opt('king_advert_destination_link').'" >');                            
                            $this->output('<img src="'.qa_opt('king_advert_image_url').'" alt="king-ads" />');
                            $this->output('</a>');
                        
                        }                        
                        
                        $this->output('</div>');
   
                    } 
                    $count++; 
                } 
                $this->output('</DIV> <!-- END king-q-list -->', ''); 

            
			$this->output('<script src="'.$this->rooturl.'jquery.infinitescroll.min.js"></script>');
			$this->output('<script type="text/javascript">
    var ias = $.ias({
      container: "#container",
      item: ".box",
      pagination: ".king-page-links-list",
      next: ".king-page-next",
      delay: 0,
	  negativeMargin: 200
    });
	
	ias.extension(new IASSpinnerExtension());
	        </script>');			
        }    
        } else {
            
            qa_html_theme_base::q_list_items($q_items);
            
        }
         
    }



}