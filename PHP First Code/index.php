<?php
    /**
     *  @author Nhien Lam, Hanh Nguyen, Aung Lin
     */

    // Specify location of file containing all the causes
    define("CAUSE_FILE", "causes.txt");
    // Determine which activity to perform and call it
    $activity = (isset($_REQUEST['a']) && in_array($_REQUEST['a'], [
    "landing", "addCause", "thumbsUp", "confirm"])) ? $_REQUEST['a'] . "Controller" : "landingController";
    $activity();

    /**
     * Process perform activities related to the landing page
     */
    function landingController()
    {
        $data["CAUSE_ENTRIES"] = getCauseEntries();
        $data["CAUSE_ENTRIES"] = processNewCauseEntries($data["CAUSE_ENTRIES"]);

        $layout = (isset($_REQUEST['f']) && in_array($_REQUEST['f'], ["html"]))
            ? $_REQUEST['f'] . "Layout" : "htmlLayout";
        $layout($data, "landingView");
    }

    /**
     * Get an array of all the cause entries currently stored on disk.
     *
     * @return array cause entries [ title1 => topic1, title2 => topic2 ...] if
     * file exists and unserializable, [] otherwise
     */
    function getCauseEntries()
    {
        if (file_exists(CAUSE_FILE)) {
            $entries = json_decode(file_get_contents(CAUSE_FILE));
            if ($entries) {
                return $entries;
            }
        }
        return [];
    }

    /**
     * Determines if a new cause was sent from landing form. If so,
     * adds the new cause to the ...................
     * @param array $entries an array of current blog entries:
     *  blog entries [ title1 =>topic1, title2 => topic2 ...]
     * @return array blog entries (updated) [ title1 => topic2, title2 => topic2 ...]
     *  if file exists and unserializable, [] otherwise
     */
    function processNewCauseEntries($entries)
    {
        $title = (isset($_REQUEST['title'])) ?
            filter_var($_REQUEST['title'], FILTER_SANITIZE_STRING) : "";
        $topic = (isset($_REQUEST['topic'])) ?
            filter_var($_REQUEST['topic'], FILTER_SANITIZE_STRING) : "";
        if ($title == "" || $topic == "")
        {
            return $entries;
        }
        $entries = array_merge([$title => $topic], $entries);
        file_put_contents(CAUSE_FILE, json_encode($entries));
        return $entries;
    }

    /**
     * Set up and then display the view corresponding to a cause
     */
    function entryController()
    {
        $data["TITLE"] = (isset($_REQUEST['title'])) ?
            filter_var($_REQUEST['title'], FILTER_SANITIZE_STRING) : "";
        $entries = getCauseEntries()();
        if (!isset($entries[$data["TITLE"]]))
        {
            landingController();
            return;
        }
        $data["TOPIC"] = $entries[$data["TITLE"]];
        $layout = (isset($_REQUEST['f']) && in_array($_REQUEST['f'], ["html"])) ?
            $_REQUEST['f'] . "Layout" : "htmlLayout";
        $layout($data, $view ."View");
        $layout($data, "promoteView");

    }

    /**
     * Output the top and bottom boilerplate of a Web page. Within
     * the body of the document the passed $view is draw
     *
     * @param array $data an associative array of field variables which might
     *  be echo'd by either this layout in the title, or by the view that is
     *  draw in the body
     * @param string $view name of view function to call to draw body of web page
     */
    function htmlLayout($data, $view)
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Promote-a-Cause
            <?php if (!empty($data['TITLE']))
            {
                echo ":" . $data['TITLE'];
            } ?>
            </title>
        </head>
        <body>
            <?php $view($data); ?>
        </body>
        </html>
        <?php
    }

    /**
     * Draw the main landing page with Promote-a-Cause form on it as well as
     * the titles of all causes that have been saved in descending order of thumbs up.
     *
     * @param array $data an associative array of field variables which might
     * be echo'd by this function.
     */
    function landingView($data)
    {
        ?>
        <h1><a href="indexsample.php">Promote-a-Cause</a></h1>
        <h2>Current Campaigns</h2>
        <!-- <form method="get">
            <div>
                <input type="submit" name="add" value="⊞">
            </div>
        </form> -->
        <h1><a href="indexsample.php?a=addCause&title=<?=urlencode($title)?>">⊞</a></h1>

        <table>
            <col style="width:30%">
            <col style="width:40%">
            <col style="width:30%">
            <tr>
            <th>Thumbs Up</th>
            <th>Topic</th>
            <th>Actions</th>
            </tr>
            <?php
            if (!empty($data["CAUSE_ENTRIES"]))
            {
                foreach ($data["CAUSE_ENTRIES"] as $title => $topic)
                {
                    ?>
                    <tr>
                    <td><?php
                    // condition to count the number of thumbs
                    if(file_exists("topic_file/ctn".md5($title).".text"))
                    {
                        echo file_get_contents("topic_file/ctn".md5($title).".text",
                            FALSE, NULL, 4);
                    }
                    else
                    {
                        echo 0;
                    }?>
                    </td>
                    <td><a href="indexsample.php?a=thumbsUp&title=<?=urlencode($title)
                        ?>"><?=$title ?></a></td>
                    <td><a href="indexsample.php?a=confirm&title=<?=
                        urlencode($title)
                        ?>"><input type="submit" name="delete" value="Delete">
                    </td>
                    </tr><?php
                }
            }?>
            </table>
            <?php
    }

    function addCauseView()
    {
        /* function definition */
    }

    function deleteView()
    {
        /* function definition */
    }

    function promoteView()
    {
        /* function definition */
    }
