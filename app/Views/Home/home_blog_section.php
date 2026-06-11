<!-- 
Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
@Description: The code of the released Hospital software, does NOT lie under
GLP (General Public License) But it has proprietary copyrights. The purpose of the
Informing for public that, the Hospital web based mobile responsible application its associated
different roles are protected by the mentioned copyrights. *
@Version: Miracle Hospital - 1.0
@Author: Neoark Software
@Address: Plot #8, Street #1, Ganga Sahay Colony (Near Govt Senior Secondary
School), Mandoli (Industrial Area) North East Delhi - 110093 (India)
@Email: sales@neoarksoftware.com | support@neoarksoftware.com
@website: www.neoarks.com
@Phone: +91-880-090-0164
Date: 21st August, 2023 
-->

<?php if (isset($blogs[0]->blog_content) && !empty($blogs[0]->blog_content)) { ?>
<section id="homeBLog">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="homBlog-area">
          <!-- Start Service Title -->
          <div class="section-heading pdng_tp">
            <h2>News From Blog</h2>
            <div class="line"></div>
          </div>
          <!-- Start Home Blog Content -->
          <div class="homeBlog-content">
            <div class="row">
              <!-- Start Single Blog -->
              <?php if ($blogs) :
                count($blogs);
                foreach ($blogs as $news_blog) : ?>
                  <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="single-Blog">
                      <div class="single-blog-left">
                        <ul class="blog-comments-box">
                          <li><?= $news_blog->created_month; ?> <h2><?= $news_blog->created_date; ?></h2>
                            <?= $news_blog->created_year; ?></li>
                          <li><span class="fa fa-eye"></span>1523</li>
                          <li><a href="#"><span class="fa fa-comments"></span>5</a></li>
                        </ul>
                      </div>
                      <div class="single-blog-right">
                        <div class="blog-img">
                          <figure class="blog-figure">
                            <!-- <a href="#"><img src="<//?= base_url('public/assets/home_image/images/choose-us-img3.jpg'); ?>" alt="img"></a> -->
                            <span class="image-effect"></span>
                                <a class="tooltipped" data-position="top" data-tooltip="<?= $news_blog->blog_image; ?>">
                                <?php
                                if (isset($news_blog->blog_image) && !empty($news_blog->blog_image)) {
                                  if (file_exists(FCPATH . 'uploads/frontend/blog_image/' . $news_blog->blog_image)) { ?>
                                    <img src="<?= base_url() . 'public/uploads/frontend/blog_image/' . $news_blog->blog_image; ?>" class="responsive-img patient_imgblog" id="profile_pic">

                                  <?php  } //Inner if - Closed
                                  else {  ?>
                                    <img src="<?= base_url() . 'public/assets/home_image/images/choose-us-img3.jpg'; ?>" class="responsive-img patient_imgblog" id="profile_pic">
                                  <?php } //Inner else - Closed

                                } //Outer if - Closed
                                else { ?>
                                  <img src="<?= base_url() . 'public/assets/home_image/images/choose-us-img3.jpg'; ?>" class="responsive-img patient_imgblog" id="profile_pic" >
                                <?php } //Outer else - Closed  
                                ?>
                            </a>
                          </figure>
                        </div>
                        <div class="blog-author">
                          <ul>
                            <li>By <a href="#"><?= $news_blog->doctor_name; ?></a></li>
                            <li>In <a href="#"><?= $news_blog->department_name; ?></a></li>
                          </ul>
                        </div>
                        <div class="blog-content">
                          <h2><?= $news_blog->blog_title; ?></h2>
                          <p class="paratext"><?= $news_blog->blog_content; ?></p>
                          <div class="readmore_area">
                            <a class="brdr_clr" href="<?= base_url('Home/view_blog/' . $news_blog->id); ?>" data-hover="Read More"><span>Read More</span></a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Start Single End -->
                <?php endforeach; ?>
              <?php else : ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php } ?>