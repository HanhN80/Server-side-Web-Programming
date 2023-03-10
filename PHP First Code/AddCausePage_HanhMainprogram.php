<?php
/**
 * index.php
 * This program is used to maintain a simple web blog. It has two
 * pages a landing page, where people can add new blog posts as
 * well as see a list of previous posts and an entry page where
 * people can read an old post
 */
/**
 * Specify location of file containing all the blog posts
 */
define("AddCausePage", "cause.txt");
    
// Determine which activity to perform and call it
$activity = (isset($_REQUEST['a']) && in_array($_REQUEST['a'], [
    "main", "entry"])) ? $_REQUEST['a'] . "Controller" : "mainController";
$activity();
/**
 * Used to process perform activities realated to the blog landing page
 */
function mainController()
{
    $data["CAUSE_ENTRIES"] = getBlogEntries();
    $data["CAUSE_ENTRIES"] = processNewBlogEntries($data["CAUSE_ENTRIES"]);
    // the above cleans out the texts only
    
    $layout = (isset($_REQUEST['a']) && in_array($_REQUEST['a'], [
        "html"])) ? $_REQUEST['a'] . "Layout" : "htmlLayout";
    $layout($data, "landingView");
}
/**
 * Used to get an array of all the blog entries currently stored on disk.
 *
 * @return array blog entries [ title1 => post1, title2 => post2 ...] if
 *   file exists and unserializable, [] otherwise
 */
function getBlogEntries()
{
    if (file_exists(AddCausePage)) {
        $entries = unserialize(file_get_contents(AddCausePage));
        if ($entries) {
            return $entries;
        }
    }
    return [];
}
/**
 * Determines if a new blog post was sent from landing page. If so,
 * adds the new post, to the end of a current list of posts, and saves the
 * serialized result to AddCausePage
 *
 * @param array $entries an array of current blog entries:
 *  blog entries [ title1 =>post1, title2 => post2 ...]
 * @return array blog entries (updated) [ title1 => post1, title2 => post2 ...]
 *  if file exists and unserializable, [] otherwise
 */
function processNewBlogEntries($entries)
    // cleans the text only!
{
    // cause entry field
    $cause = (isset($_REQUEST['cause'])) ?
        filter_var($_REQUEST['cause'], FILTER_SANITIZE_STRING) : "";
    
    // description field
    $post = (isset($_REQUEST['post'])) ?
        filter_var($_REQUEST['post'], FILTER_SANITIZE_STRING) : "";
    if ($cause == "" || $post == "") {
        return $entries;
    }
    // merging new entry(cause and post) into ENTRY array
    $entries = array_merge([$cause => $post], $entries);
    
    // write to the BLOG FILE
    file_put_contents(AddCausePage, serialize($entries));
    json_encode($entries);
    
    return $entries;
}
    
/**
 * Used to set up and then display the view corresponding to a single blog
 * post.
 */
function entryController()
{
    $data["TITLE"] = (isset($_REQUEST['cause'])) ?
        filter_var($_REQUEST['cause'], FILTER_SANITIZE_STRING) : "";
    $entries = getBlogEntries();
    if (!isset($entries[$data["TITLE"]])) {
        mainController();
        return;
    }
    $data["POST"] = $entries[$data["TITLE"]];
    $layout = (isset($_REQUEST['a']) && in_array($_REQUEST['a'], [
        "html"])) ? $_REQUEST['a'] . "Layout" : "htmlLayout";
    $layout($data, "entryView");
}
/**
 * Used to output the top and bottom boilerplate of a Web page. Within
 * the body of the document the passed $view is draw
 *
 * @param array $data an associative array of field variables which might
 *  be echo'd by either this layout in the title, or by the view that is
 *  draw in the body
 * @param string $view name of view function to call to draw body of web page
 */
function htmlLayout($data, $view)
{
    ?><!DOCTYPE html>
<html>
    <head><title>Add Cause Page <?php if (!empty($data['TITLE'])) {
        echo ":" . $data['TITLE'];
    } ?></title></head>
    <body>
    <?php
    $view($data);
    ?>
    </body>
</html><?php
}
/**
 * Used to draw the main landing page with blog form on it as well as previous
 * blog posts
 *
 * @param array $data an associative array of field variables which might
 *  be echo'd by this function. In this case, we will use $data["BLOG_ENTRIES"]
 *  to output old blog entries
 */
function landingView($data)
{
    ?>
    <h1><a href="AddCausePage.php"><p style="color: blue;">Promote-a-Cause</p></a></h1>
    <form>
    <div>
    <label for='post-title'>Cause</label>:
    <input id='post-title' name="cause" placeholder="Enter cause" type="text" />
    </div>
    <div>
    <label for='post-body'>Description</label>:<br />
    <textarea id='post-body' name="post" rows="30" cols="80"
    placeholder="Enter description" ></textarea>
    </div>
    <div>
    <button>Save</button>

    <?--- Cancel button--->

   <input type="button" name="cancel" value="Cancel" onClick="window.location='http://localhost:8080/index.php';" />
    
    <br>
    <br>
    <br>
    <br>
    <a href="index.php">Go back to LandingPage</a>
    </div>
    </form>
    <?php
        // print each entry in the BLOG
        
//    if (!empty($data["CAUSE_ENTRIES"])) {
//        foreach ($data["CAUSE_ENTRIES"] as $cause => $post) {
//            ?><div><a href="index.php?a=entry&title=<?=urlencode($cause)
//            ?>"><?=$cause ?></a></div><?php
//        }
//    }
        
}
/**
 * Used to output to the browser an individual blog entry
 *
 * @param array $data an associative array of field variables which might
 *  be echo'd by this function. In this case, we will use $data["TITLE"]
 *  and $data['POST'] which contain the blog post
 */
function entryView($data)
{
    ?>
    <h1><a href="index.php">Simple Blog</a> : <?=$data['TITLE'] ?></h1>
    <h2><?=$data['TITLE'] ?></h2>
    <div><?=$data['POST'] ?>
    </div>
    <?php
}
