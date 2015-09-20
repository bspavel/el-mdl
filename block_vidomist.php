<?php defined('MOODLE_INTERNAL') or die();
class block_vidomist extends block_base
{

    function init()
    {
        $this->title = get_string('pluginname', 'block_vidomist');
    }

    function get_content()
    {
        global $CFG, $DB, $USER, $COURSE;
        if ($this->content !== null)
        {
            return $this->content;
        }
        if (empty($this->instance))
        {
            $this->content = '';
            return $this->content;
        }
        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';
        //$currentcontext = $this->page->context->get_course_context(false);
        if (! empty($this->config->text))
        {
            $this->content->text = $this->config->text;
        }
        $this->content = '';

        require("lib_role.php");

    if($CrsAcs)
    {
        $flag=false;
    if
    (
        (!empty($this->config->hour))&&
        (!empty($this->config->ects))&&
        (!empty($this->config->spec))&&
        (!empty($this->config->grp))
    /*(!empty($this->config->status))*/
    )
    {$flag=true;}
        if ($flag)
        {
            $this->content->text.=
            "<form 	method='post'
                action='{$CFG->wwwroot}/blocks/vidomist/down.php?cid={$COURSE->id}'>".
            "<center><button type='submit'><img src='https://cdn4.iconfinder.com/data/icons/Basic_set2_Png/64/arrow_down.png' border='0' /></button></center>".
            "<input type='hidden' name='hour' value='{$this->config->hour}'>".
            "<input type='hidden' name='ects' value='{$this->config->ects}'>".
            "<input type='hidden' name='spec' value='{$this->config->spec}'>".
            /*"<input type='hidden' name='formnavch' value='{$this->config->formnavch}'>".*/
            "<input type='hidden' name='formnagroup' value='{$this->config->grp}'>".
            "<input type='hidden' name='status' value='{$this->config->status}'>".
            "<br />".
            "</form>";
        }
        else
        {
            $this->content->text.='***';
        }
    }
    else
    {
        $this->content=null;
    }
        return $this->content;
    }
    public function applicable_formats()
    {
        return array('all' => false,
                     'site' => true,
                     'site-index' => true,
                     'course-view' => true, 
                     'course-view-social' => false,
                     'mod' => true, 
                     'mod-quiz' => false);
    }
    public function instance_allow_multiple()
    {
          return true;
    }
    function has_config() {return true;}
    public function cron()
    {
        mtrace( "Hey, my cron script is running" );
        // do something
        return true;
    }
}