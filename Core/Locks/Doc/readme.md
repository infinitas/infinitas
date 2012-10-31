The Locks plugin provides a way to lock records while they are being edited. While a record is locked, only the user that originally locked the record would be able to continue editing the record. This stops two people potentially editing the same record and saving over each others work.

#### Example Scenario

`User A` clicks edit and begins altering a record. Mean while `User B` opens the same document to have a look. Shortly after `User A` is done editing the record, saves their work and moves on. `User B` is done looking at the record and instead of clicking `cancel` or `back` they click save. The original unedited version is saved to the database overwriting the recently edited version from `User A`.

There are a number of ways users can step on each others toes, especially when there is more than one person with permission to edit particular records. The Locks plugin prevents this sort of thing from happening by setting a locked flag for the record when it is opened.

#### What locks do

Similar to above, `User A` opens a record for editing. At the same time a flag is set indicating that the record is currently locked and anyone else trying to edit that same record would instead be shown an error. Once `User A` has finished editing the record the flag is removed and other users are able to once again open and edit the record freely.

#### Removing locks

During normal use once a record has been saved the lock is removed. If the user is not making any changes and clicks `cancel` the lock is also removed. There are times when the locks are not removed, for example if the user clicks `back` in the browser. If this is the case and the Crons plugin has been configured, locks will automatically be removed after they have been around for a set time. 

> If the Crons plugin is not configured, there is also a [management page](/admin/locks) for the locks where site administrators can remove the locks manually. This is simply a case of selecting the lock in question (or all of them) and clicking delete.