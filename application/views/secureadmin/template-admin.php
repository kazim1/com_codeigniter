<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $this->load->view(ADMIN_VIEW_PATH.'common/head.php'); ?>
    </head>

    <body>
        
	        <!-- HEADER -->
            <div class="header">
            	<div class="main-wrapper">
                <?php $this->load->view(ADMIN_VIEW_PATH.'common/header.php'); ?>
                </div>
            </div>    
            <!-- HEADER -->
        
        <div class="main-wrapper">
            <!-- LEFT COLUMN -->
            <div class="left">
                <?php $this->load->view(ADMIN_VIEW_PATH.'common/left.php'); ?>
            </div>
            <!-- LEFT COLUMN -->
            
            <!-- MAIN CONTENT SECTION -->
            <div class="right-content">
                <div class="row">
                    <?php $this->load->view(ADMIN_VIEW_PATH.'common/message'); ?>
                </div>
                <?php $this->load->view($this->view_path); ?>
            </div>
            <!-- MAIN CONTENT SECTION -->
            
            <!-- FOOTER -->
            <div class="footer">
            <?php $this->load->view(ADMIN_VIEW_PATH.'common/footer.php'); ?>
            </div>
            <!-- FOOTER -->
            
        </div>
    </body>
</html>
