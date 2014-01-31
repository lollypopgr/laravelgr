# Eloquent ORM

- [Εισαγωγή](#introduction)
- [Βασική χρήση](#basic-usage)
- [Μαζική εκχώρηση στοιχείων](#mass-assignment)
- [Insert, Update, Delete](#insert-update-delete)
- [Soft Deleting](#soft-deleting)
- [Timestamps](#timestamps)
- [Query Scopes](#query-scopes)
- [Συσχετίσεις](#relationships)
- [Querying Relations](#querying-relations)
- [Προθυμοποίηση φόρτωσης](#eager-loading)
- [Εισάγοντας σχετικά μοντέλα](#inserting-related-models)
- [Αναβαθμίζοντας τα Parent Timestamps](#touching-parent-timestamps)
- [Δουλεύοντας με Pivot Tables](#working-with-pivot-tables)
- [Συλλογές](#collections)
- [Accessors & Mutators](#accessors-and-mutators)
- [Mutators ημερομηνίας](#date-mutators)
- [Μοντέλο συμβάντος](#model-events)
- [Μοντέλο παρατηρητής](#model-observers)
- [Μετατρέποντας σε πίνακες / JSON](#converting-to-arrays-or-json)

<a name="introduction"></a>
## Εισαγωγή

Το Eloquent ORM που περιλαμβάνεται στο Laravel παρέχει μια όμορφη και απλή υλοποίηση του ActiveRecord για να χρησιμοποιήσετε με την βάση δεδομένων σας. Κάθε πίνακας στην βάση δεδομένων έχει ένα "Μοντέλο" στο οποίο αντιστοιχεί και το οποίο χρησιμοποιεί για την επικοινωνία με αυτόν τον πίνακα.

Πριν ξεκινήσετε, μην ξεχάσετε να διαμορφώσετε κατάλληλα την σύνδεση προς την βάση δεδομένων μέσα από το αρχείο `app/config/database.php`.

<a name="basic-usage"></a>
## Βασική χρήση

Για να ξεκινήσετε, δημιουργήστε ένα μοντέλο Eloquent. Τα μοντέλα συνήθως βρίσκονται μέσα στον φάκελο `app/models`, αλλά μπορείτε να τα τοποθετήσετε όπου εσείς θέλετε, αρκεί βέβαια να τα φορτώνετε αυτόματα μέσα από το `composer.json` αρχείο σας.

#### Ορίζοντας ένα μοντέλο Eloquent

	class User extends Eloquent {}

Σημειώστε ότι δεν ορίσαμε στο Eloquent ποιόν πίνακα να χρησιμοποιήσει για το μοντέλο μας με όνομα `User`. Η χρήση πεζών γραμμάτων και ο πληθυντικός της κλάσης θα χρησιμοποιηθούν ως το όνομα του πίνακα, εκτός αν δωθεί κάποιο άλλο πιο συγκεκριμένο. Έτσι, σε αυτή την περίπτωση, το Eloquent θα υποθέσει ότι το μοντέλο με όνομα `User` αποθηκεύει δεδομένα στον πίνακα με όνομα `users`. Μπορείτε να ορίσετε κάποιον άλλον πίνακα με την χρήση της ιδιότητας `table` μέσα στο μοντέλο σας:

	class User extends Eloquent {

		protected $table = 'my_users';

	}

> **Σημείωση:** Το Eloquent θα υποθέσει επίσης ότι κάθε πίνακας έχει μια στήλη με ένα πρωτεύον κλειδί που ονομάζεται `id`. Μπορείτε να ορίσετε μια ιδιότητα με όνομα `primaryKey` για να παρακάμψετε την προεπιλεγμένη τιμή. Με παρόμοιο τρόπο, μπορείτε να ορίσετε μια ιδιότητα με όνομα `connection` για να παρακάμψετε το όνομα της σύνδεσης προς την βάση δεδομένων που πρέπει να χρησιμοποιηθεί όταν αξιοποιείτε το μοντέλο model σας.

Μόλις οριστεί ένα μοντέλο, είσαστε έτοιμοι να ξεκινήσετε να δημιουργήτε και να ανακτάτε δεδομένα μέσα στον πίνακά σας. Σημειώστε ότι θα χρειαστεί να ορίσετε τις στήλες με όνομα `updated_at` και `created_at` μέσα στον πίνακά σας ως προεπιλογή. Αν δεν επιθυμείτε να έχετε αυτές τις στήλες να υποστηρίζονται αυτόματα, ορίστε μια ιδιότητα με όνομα `$timestamps` μέσα στο μοντέλο σας και δώστε του την τιμή `false`.

#### Ανακτώντας όλα τα μοντέλα

	$users = User::all();

#### Ανακτώντας δεδομένα με την χρήση πρωτεύοντος κλειδιού

	$user = User::find(1);

	var_dump($user->name);

> **Σημείωση:** Όλες οι μέθοδοι που είναι διαθέσιμοι στο [query builder](/docs/queries) είναι επίσης διαθέσιμοι για την χρήση των μοντέλων Eloquent.

#### Ανακτώντας ένα μοντέλο με την χρήση πρωτεύοντος κλειδιού ή 'ρίξε' μια εξαίρεση

Μερικές φορές μπορεί να θελήσετε να κάνετε χρήση κάποιας εξαίρεσης αν δεν βρεθεί ένα μοντέλο, δίνοντας σας την δυνατότητα να 'πιάσετε' την εξαίρεση αυτή με την χρήση του χειριστή `App::error` και να δείξετε στον χρήστη κάποια σελίδα 404.

	$model = User::findOrFail(1);

	$model = User::where('votes', '>', 100)->firstOrFail();

Για να εγγράψετε τον χειριστή λάθους στην εφαρμογή σας, κάντε χρήση του `ModelNotFoundException`

	use Illuminate\Database\Eloquent\ModelNotFoundException;

	App::error(function(ModelNotFoundException $e)
	{
		return Response::make('Not Found', 404);
	});

#### Εκτελώντας Query με την χρήση μοντέλων Eloquent

	$users = User::where('votes', '>', 100)->take(10)->get();

	foreach ($users as $user)
	{
		var_dump($user->name);
	}

Μπορείτε επίσης να χρησιμοποιήσετε τις λειουργίες του query builder σχετικά με τα σύνολα.

#### Σύνολα Eloquent

	$count = User::where('votes', '>', 100)->count();

Αν δεν μπορείτε να κάνετε χρήση του query αυτού, μπορείτε να δοκιμάσετε κάτι παρόμοιο όπως το `whereRaw`:

	$users = User::whereRaw('age > ? and votes = 100', array(25))->get();

#### 'Τεμαχίζοντας' τα αποτελέσματα

Αν χρειάζετε να επεξεργαστείτε πολλά (χιλιάδες) δεδομένα Eloquent, η χρήση της εντολής `chunk` θα σας επιτρέψει να το κάνετε χωρίς να καταναλώσετε όλη σας την μνήμη RAM:

	User::chunk(200, function($users)
	{
		foreach ($users as $user)
		{
			//
		}
	});

Η πρώτη παράμετρος που δίνεται στην μέθοδο είναι ο αριθμός των καταγραφών που θέλετε να λάβετε σε κάθε 'τεμάχιο'. Η χρήση Closure ως δεύτερη παραμέτρος θα καλεστεί για κάθε τεμάχιο που ανακτάται από την βάση δεδομένων.

#### Ορίζοντας το Query της σύνδεσης

Μπορείτε επίσης να ορίσετε ποιά σύνδεση για την βάση δεδομένων πρέπει να χρησιμοποιηθεί όταν 'τρέχετε' ένα Eloquent query. Απλά χρησιμοποιήστε την μέθοδο `on`:

	$user = User::on('connection-name')->find(1);

<a name="mass-assignment"></a>
## Μαζική εκχώρηση στοιχείων

Όταν δημιουργήτε ένα νέο μοντέλο, δίνετε ως παράμετρο στον κατασκευαστή του μοντέλου έναν πίνακα με ιδιότητες. Αυτές οι ιδιότητες ανατίθενται στο μοντέλο μέσω της μαζικής εκχώρησης στοιχείων. Αυτό είναι βολικό; παρόλαυτα, μπορεί να είναι ένα πολύ **σοβαρό** θέμα ασφαλείας όταν απλώς περνάτε δεδομένα εισόδου των χρηστών μέσα στο μοντέλο σας. Αν τα δεδομένα εισόδου ενός χρήστη περνούν μέσα στο μοντέλο χωρίς επιτήρηση, ο χρήστης είναι ελεύθερος να διαμορφώσει **οποιαδήποτε** και ίσως **όλες** τις ιδιότητες του μοντέλου σας. Για αυτό τον λόγο, όλα τα μοντέλα Eloquent είναι προστατευμένα από την χρήση μαζικής εκχώρησης στοιχείων ως προεπιλογή.

Για να ξεκινήσετε, ορίστε τις ιδιότητες `fillable` ή `guarded` μέσα στο μοντέλο σας.

Η ιδιότητα `fillable` ορίζει ποιά χαρακτηριστικά πρέπει να μπορούν να εισάγονται μαζικά. Αυτό μπορεί να οριστεί στο επίπεδο της κλάσης ή του στιγμιοτύπου.

#### Ορίζοντας ένα χαρακτηριστικό Fillable σε ένα μοντέλο

	class User extends Eloquent {

		protected $fillable = array('first_name', 'last_name', 'email');

	}

Σε αυτό το παράδειγμα, μόνο τα τρία αυτά χαρακτηριστικά μπορούν να δηλωθούν μαζικά.

Το αντίθετο του χαρακτηριστικού `fillable` είναι `guarded`, και χρησιμοποιείται ως "μαύρη-λίστα":

#### Ορίζοντας χαρακτηριστικά Guarded σε ένα μοντέλο

	class User extends Eloquent {

		protected $guarded = array('id', 'password');

	}

Στο παραπάνω παράδειγμα, τα χαρακτηριστικά `id` και `password` **δεν** θα μπορούν να δηλωθούν μαζικά. Όλα τα άλλα χαρακτηριστικά θα μπορούν. Μπορείτε επίσης να μπλοκάρετε **όλα** τα χαρακτηριστικά από το να μπορούν να δηλώνονται μαζικά, με την χρήση της μεθόδου guard:

#### Μπλοκάροντας όλα τα χαρακτηριστικά από μαζική εκχώρηση

	protected $guarded = array('*');

<a name="insert-update-delete"></a>
## Insert, Update, Delete

Για να δημιουργήσετε μια νέα καταγραφή στην βάση δεδομένων μέσα από ένα μοντέλο, απλά δημιουργήστε ένα νέο στιγμιότυπο του μοντέλου και καλέστε την μέθοδο `save`.

#### Αποθηκεύοντας ένα νέο μοντέλο

	$user = new User;

	$user->name = 'John';

	$user->save();

> **Σημείωση:** Συνήθως, τα Eloquent μοντέλα σας θα έχουν κλειδιά auto-incrementing. Παρόλαυτα, αν θελήσετε να ορίσετε τα δικά σας κλειδιά, ορίστε στην ιδιότητα `incrementing` μέσα στο μοντέλο σας την τιμή `false`.

Μπορείτε επίσης να χρησιμοποιήσετε την μέθοδο `create` ώστε να αποθηκεύσετε ένα νέο μοντέλο με μόνο μια γραμμή κώδικα. Το εισαγμένο στιγμιότυπο του μοντέλου θα σας επιστραφεί από την μέθοδο. Παρόλαυτα, πριν το κάνετε αυτό, θα χρειαστεί να ορίσετε είτε κάποιο `fillable` είτε κάποιο `guarded` χαρακτηριστικό μέσα στο μοντέλο σας, μιας και όλα τα μοντέλα Eloquent προστατεύονται από μαζική εκχώρηση στοιχείων.

Αφού αποθηκεύσετε ή δημιουργήσετε ένα νέο μοντέλο που χρησιμοποιεί auto-incrementing IDs, μπορείτε να ανακτήσετε το ID έχοντας πρόσβαση στο χαρακτηριστικό `id` του αντικειμένου:

	$insertedId = $user->id;

#### Ορίζοντας τα χαρακτηριστικά Guarded στο μοντέλο

	class User extends Eloquent {

		protected $guarded = array('id', 'account_id');

	}

#### Χρησιμοποιώντας την μέθοδο μοντέλου Create

	// Δημιουργήστε έναν νέο χρήστη στην βάση δεδομένων...
	$user = User::create(array('name' => 'John'));

	// Ανακτήστε τον χρήστη από τα χαρακτηριστικά του, ή δημιουργήστε έναν νέο σε περίπτωση που δεν υπάρχει ήδη...
	$user = User::firstOrCreate(array('name' => 'John'));

	// Ανακτήστε τον χρήστη από τα χαρακτηριστικά του, ή δημιουργήστε ένα νέο στιγμιότυπο του...
	$user = User::firstOrNew(array('name' => 'John'));

Για να αναβαθμίσετε ένα μοντέλο, μπορείτε να το ανακτήσετε, να αλλάξετε κάποιο χαρακτηριστικό του, και έπειτα να κάνετε χρήση της μεθόδου `save`:

#### Αναβαθμίζοντας ένα ανακτηθέν μοντέλο

	$user = User::find(1);

	$user->email = 'john@foo.com';

	$user->save();

Μερικές φορές μπορεί να θελήσετε να αποθηκεύσετε όχι μόνο το μοντέλο, αλλά και όλες του τις συσχετίσεις. Για να το κάνετε αυτό, χρησιμοποιήστε την μέθοδο `push`:

#### Αποθηκεύοντας ένα μοντέλο και τις συσχετίσεις του

	$user->push();

Μπορείτε επίσης να 'τρέξετε' κάποια queries ως αναβάθμιση ενάντια σε ένα συνολο από μοντέλα:

	$affectedRows = User::where('votes', '>', 100)->update(array('status' => 2));

Για να διαγράψετε ένα μοντέλο, απλά καλέστε την μέθοδο `delete` στο στιγμιότυπο:

#### Διαγράψτε ένα ήδη υπάρχον μοντέλο

	$user = User::find(1);

	$user->delete();

#### Διαγράψτε ένα ήδη υπάρχον μοντέλο με βάση κάποιο κλειδί του

	User::destroy(1);

	User::destroy(array(1, 2, 3));

	User::destroy(1, 2, 3);

Φυσικά μπορείτε να τρέξετε ένα query διαγραφής σε ένα σύνολο από μοντέλα:

	$affectedRows = User::where('votes', '>', 100)->delete();

Αν επιθυμείτε απλά να αναβαθμίσετε τα timestamps σε ένα μοντέλο, μπορείτε να χρησιμοποιήσετε την μέθοδο `touch`:

#### Αναβαθμίζοντας μόνο τα Timestamps του μοντέλου

	$user->touch();

<a name="soft-deleting"></a>
## Soft Deleting

Όταν κάνετε soft delete σε ένα μοντέλο, στην πραγματικότητα δεν απομακρύνεται από την βάση δεδομένων σας. Αντί αυτού, ένα timestamp με όνομα `deleted_at` καταγράφεται στην βάση σας. Για να ενεργοποιήσετε τα soft deletes για ένα μοντέλο, ορίστε την ιδιότητα `softDelete` μέσα σε αυτό:

	class User extends Eloquent {

		protected $softDelete = true;

	}

Για να προσθέσετε μια στήλη με όνομα `deleted_at` στον πίνακα σας, μπορείτε να χρησιμοποιήσετε την μέθοδο `softDeletes` μέσα από ένα migration:

	$table->softDeletes();

Τώρα, όταν καλείτε την μέθοδο `delete` σε ένα μοντέλο, η στήλη με όνομα `deleted_at` θα παίρνει την τιμή του τρέχοντος timestamp. Όταν κάνετε χρήση ενός query σε ένα μοντέλο που χρησιμοποιεί soft deletes, τα "διεγραμμένα" μοντέλα δεν θα περιλαμβάνονται στα αποτελέσματα του query. Για να εξαναγκάσετε τα soft deleted μοντέλα να εμφανιστούν σε ένα σύνολο αποτελεσμάτων, χρησιμοποιήστε την μέθοδο `withTrashed` στο query:

#### Εξαναγκάζοντας τα Soft Deleted μοντέλα μέσα σε αποτελέσματα

	$users = User::withTrashed()->where('account_id', 1)->get();

Αν θελήσετε να λαμβάνετε **μόνο** τα soft deleted μοντέλα στα αποτελέσματά σας, μπορείτε να χρησιμοποιήσετε την μέθοδο `onlyTrashed`:

	$users = User::onlyTrashed()->where('account_id', 1)->get();

Για να ανακτήσετε ένα soft deleted μοντέλο και να το ενεργοποιήσετε, χρησιμοποιήστε την μέθοδο `restore`:

	$user->restore();

Μπορείτε επίσης να χρησιμοποιήσετε την μέθοδο `restore` σε ένα query:

	User::withTrashed()->where('account_id', 1)->restore();

Η μέθοδος `restore` μπορεί επίσης να χρησιμοποιηθεί σε συσχετίσεις:

	$user->posts()->restore();

Αν επιθυμείτε να αφαιρέσετε πραγματικά ένα μοντέλο από την βάση δεδομένων, χρησιμοποιήστε την μέθοδο `forceDelete`:

	$user->forceDelete();

Η μέθοδος `forceDelete` δουλεύει επίσης και με τις συσχετίσεις:

	$user->posts()->forceDelete();

Για να καθορίσετε αν ένα δοσμένο στιγμιότυπο μοντέλου έχει υποστεί soft delete, μπορείτε να χρησιμοποιήσετε την μέθοδο `trashed`:

	if ($user->trashed())
	{
		//
	}

<a name="timestamps"></a>
## Timestamps

Από προεπιλογή, το Eloquent θα συντηρήσει τις στήλες `created_at` και `updated_at` μέσα στην βάση δεδομένων σας αυτόματα. Απλά προσθέστε αυτές τις στήλες `timestamp` στον πίνακά σας και το Eloquent θα τακτοποιήσει όλα τα υπόλοιπα. Αν δεν επιθυμείτε το Eloquent να συντηρεί αυτές τις στήλες, προσθέστε την ακόλουθη ιδιότητα στο μοντέλο σας:

#### Απενεργοποιώντας τα αυτόματα Timestamps

	class User extends Eloquent {

		protected $table = 'users';

		public $timestamps = false;

	}

Αν επιθυμείτε να διαμορφώσετε την μορφή των timestamps, μπορείτε να παρακάμψετε την μέθοδο `getDateFormat` μέσα στο μοντέλο σας:

#### Παρέχοντας μια προσαρμοσμένη μορφή Timestamp

	class User extends Eloquent {

		protected function getDateFormat()
		{
			return 'U';
		}

	}

<a name="query-scopes"></a>
## Query Scopes

Τα Scopes σας επιτρέπουν να επαναχρησιμοποιήσετε έυκολα την λογική των query μέσα στα μοντέλα σας. Για να ορίσετε ένα scope, απλά εισάγετε ως πρόθεμα την λέξη `scope` σε μια μέθοδο μέσα στο μοντέλο σας:

#### Ορίζοντας ένα Query Scope

	class User extends Eloquent {

		public function scopePopular($query)
		{
			return $query->where('votes', '>', 100);
		}

		public function scopeWomen($query)
		{
			return $query->whereGender('W');
		}

	}

#### Αξιοποιώντας ένα Query Scope

	$users = User::popular()->women()->orderBy('created_at')->get();

#### Δυναμικά Scopes

Μερικές φορές μπορεί να θέλετε να ορίσετε ένα scope το οποίο επιδέχεται κάποιες παραμέτρους. Απλά προσθέστε τις παραμέτρους που θέλετε στην λειτουργία scope:

	class User extends Eloquent {

		public function scopeOfType($query, $type)
		{
			return $query->whereType($type);
		}

	}

Έπειτα δώστε τις παραμέτρους σας μέσα στην κλήση του scope:

	$users = User::ofType('member')->get();

<a name="relationships"></a>
## Συσχετίσεις

Πιθανώς οι πίνακες της βάσης δεδομένων σας να συσχετίζονται μεταξύ τους. Για παράδειγμα, μια δημοσίευση blog μπορεί να έχει πολλά σχόλια, ή μια παραγγελία μπορεί να σχετίζεται με τον χρήστη ο οποίος την έκανε. Το Eloquent κάνει την διαχείριση και την χρήση αυτών των συσχετίσεων εύκολη. Το Laravel υποστηρίζει τέσσερα είδη συσχετίσεων:

- [Ένα με ένα](#one-to-one)
- [Ένα με πολλά](#one-to-many)
- [Πολλά με πολλά](#many-to-many)
- [Αποτελείται από πολλά](#has-many-through)
- [Πολυμορφικές συσχετίσεις](#polymorphic-relations)

<a name="one-to-one"></a>
### Ένα με ένα

Μια συσχέτιση ένα-με-ένα είναι μια πολύ βασική σχέση. Για παράδειγμα, ένα μοντέλο με όνομα `User` μπορεί να έχει ένα `Phone`. Μπορούμε να ορίσουμε αυτή την συσχέτιση με το Eloquent:

#### Ορίζοντας μια συσχέτιση ένα με ένα

	class User extends Eloquent {

		public function phone()
		{
			return $this->hasOne('Phone');
		}

	}

Το πρώτο όρισμα που δίνεται στην μέθοδο `hasOne` είναι το όνομα του μοντέλου συσχέτισης. Μόλις οριστεί η συσχέτιση, μπορούμε να την ανακτήσουμε χρησιμοποιώντας τις δυναμικές ιδιότητες του Eloquent [dynamic properties](#dynamic-properties):

	$phone = User::find(1)->phone;

Η SQL που εκτελείται από αυτή την δήλωση, θα είναι κάπως έτσι:

	select * from users where id = 1

	select * from phones where user_id = 1

Σημειώστε ότι το Eloquent υποθέτει το ξένο κλειδί της συσχέτισης βασιζόμενο στο όνομα του μοντέλου. Σε αυτή την περίπτωση, το μοντέλο `Phone` υποτίθεται ότι χρησιμοποιέι το ξένο κλειδί με όνομα `user_id`. Αν θελήσετε να παρακάμψετε αυτή την σύμβαση, μπορείτε να δώσετε μια δεύτερη παράμετρο στην μέθοδο `hasOne`. Επιπλέον, μπορείτε να δώσετε και μια τρίτη παράμετρο στη μέθοδο αυτή για να ορίσετε την νέα στήλη που θα πρέπει να χρησιμοποιηθεί για την συσχέτιση:

	return $this->hasOne('Phone', 'foreign_key');

	return $this->hasOne('Phone', 'foreign_key', 'local_key');

#### Ορίζοντας το αντίστροφο μιας συσχέτισης

Για να ορίσουμε το αντίστροφο μιας συσχέτισης στο μοντέλο `Phone`, χρησιμοποιούμε την μέθοδο `belongsTo`:

	class Phone extends Eloquent {

		public function user()
		{
			return $this->belongsTo('User');
		}

	}

Στο παραπάνω παράδειγμα, το Eloquent θα ψάξει για μια στήλη με όνομα `user_id` στον πίνακα `phones`. Αν θέλετε να ορίσετε μια διαφορετική στήλη ξένου κλειδιού, μπορείτε να δώσετε μια δεύτερη παράμετρο στην μέθοδο `belongsTo`:

	class Phone extends Eloquent {

		public function user()
		{
			return $this->belongsTo('User', 'local_key');
		}

	}

Επιπλέον, μπορείτε να δώσετε και μια τρίτη παράμετρο η οποία ορίζει το όνομα της νέας στήλης με την οποία θα συσχετίζεται ο γονικός πίνακας:

	class Phone extends Eloquent {

		public function user()
		{
			return $this->belongsTo('User', 'local_key', 'parent_key');
		}

	}

<a name="one-to-many"></a>
### Ένα με πολλά

Ένα παράδειγμα μιας συσχέτισης ένα-με-πολλά είναι μια δημοσίευση blog η οποία "έχει πολλά" σχόλια. Μπορούμε να μοντελοποιήσουμε αυτή την συσχέτιση με τον ακόλουθο τρόπο:

	class Post extends Eloquent {

		public function comments()
		{
			return $this->hasMany('Comment');
		}

	}

Τώρα μπορούμε να έχουμε πρόσβαση στα σχόλια της δημοσίευσης μέσω των δυναμικών ιδιοτήτων [dynamic property](#dynamic-properties):

	$comments = Post::find(1)->comments;

Αν χρειαστείτε να προσθέσετε επιπλέον περιορισμούς με τον οποίο θα πρέπει να σχόλια να ανακτούνται, μπορείτε να καλέσετε την μέθοδο `comments` και να συνεχίσετε να προσθέτετε συνθήκες:

	$comments = Post::find(1)->comments()->where('title', '=', 'foo')->first();

Όπως και πριν, μπορείτε να παρακάμψετε το κατά σύμβαση ξένο κλειδί, δίνοντας μια δεύτερη παράμετρο στην μέθοδο `hasMany`. Και όπως ακριβώς με την συσχέτιση `hasOne`, μια νέα στήλη μπορεί να οριστεί για χρήση:

	return $this->hasMany('Comment', 'foreign_key');

	return $this->hasMany('Comment', 'foreign_key', 'local_key');

Για να ορίσετε το αντίστροφο της συσχέτισης στο μοντέλο `Comment`, χρησιμοποιείστε την μέθοδο `belongsTo`:

#### Ορίζοντας το αντίστροφο μιας συσχέτισης

	class Comment extends Eloquent {

		public function post()
		{
			return $this->belongsTo('Post');
		}

	}

<a name="many-to-many"></a>
### Πολλά με πολλά

Οι συσχετίσεις πολλά-με-πολλά είναι ένα πιο σύνθετο θέμα. Ένα παράδειγμα μιας τέτοιας συσχέτισης είναι ένας χρήστης με πολλούς ρόλους, όπου οι ρόλοι επίσης μοιράζονται και με άλλους χρήστες. Για παράδειγμα, πολλοί χρήστες μπορεί να έχουν τον ρόλο "Admin". Χρειάζονται τρεις πίνακες στην βάση δεδομένων για αυτή την συσχέτιση: `users`, `roles`, και `role_user`. Ο πίνακας `role_user` δημιουργείται από την αλφαβητική σειρά των ονομάτων των σχετικών μοντέλων, και πρέπει να έχει στήλες με τα ονόματα `user_id` και `role_id`.

Μπορούμε να ορίσουμε μια συσχέτιση πολλά-με-πολλά χρησιμοποιώντας την μέθοδο `belongsToMany`:

	class User extends Eloquent {

		public function roles()
		{
			return $this->belongsToMany('Role');
		}

	}

Τώρα, μπορούμε να ανακτήσουμε τους ρόλους μέσα από το μοντέλο του `User`:

	$roles = User::find(1)->roles;

Αν θέλετε να χρησιμοποιήσετε κάποιο άλλο όνομα για τον πίνακα pivot σας, μπορείτε να ορίσετε μια δεύτερη παράμετρο στην μέθοδο `belongsToMany`:

	return $this->belongsToMany('Role', 'user_roles');

Μπορείτε επίσης να παρακάμψετε και τα ονόματα των κλειδιών συσχέτισης:

	return $this->belongsToMany('Role', 'user_roles', 'user_id', 'foo_id');

Φυσικά, μπορείτε να ορίσετε την αντίστροφη συσχέτιση στο μοντέλο `Role`:

	class Role extends Eloquent {

		public function users()
		{
			return $this->belongsToMany('User');
		}

	}

<a name="has-many-through"></a>
### Αποτελείται από πολλά

Η συσχέτιση "αποτελείται από πολλά" μας δίνει την δυνατότητα πρόσβασης μακρυνών συσχετίσεων μέσω μιας ενδιάμεσης σχέσης. Για παράδειγμα, ένα μοντέλο με όνομα `Country` μπορεί να έχει πολλά `Posts` μέσω ενός μοντέλου `Users`. Οι πίνακες για αυτή την συσχέτιση θα είναι κάπως έτσι:

	countries
		id - integer
		name - string

	users
		id - integer
		country_id - integer
		name - string

	posts
		id - integer
		user_id - integer
		title - string

Παρόλο που ο πίνακας `posts` δεν περιέχει μια στήλη με όνομα `country_id`, η συσχέτιση `hasManyThrough` θα μας επιτρέψει να έχουμε πρόσβαση σε ένα country post μέσω του `$country->posts`. Ας ορίσουμε την συσχέτιση:

	class Country extends Eloquent {

		public function posts()
		{
			return $this->hasManyThrough('Post', 'User');
		}

	}

Αν θέλετε να ορίσετε τα κλειδιά της συσχέτισης χειροκίνητα, μπορείτε να τα ορίσετε ως τρίτη και τέταρτη παράμετρο στην μέθοδο:

	class Country extends Eloquent {

		public function posts()
		{
			return $this->hasManyThrough('Post', 'User', 'country_id', 'user_id');
		}

	}

<a name="polymorphic-relations"></a>
### Πολυμορφικές συσχετίσεις

Οι πολυμορφικές συσχετίσεις επιτρέπουν σε ένα μοντέλο να ανήκει σε παραπάνω από ένα ακόμα μοντέλο, σε μια μόνο σχέση. Για παράδειγμα, μπορεί να έχετε ένα μοντέλο φωτογραφιών που ανήκει είτε σε ένα μοντέλο staff είτε σε ένα μοντέλο order. Θα πρέπει να ορίσουμε αυτή την συσχέτιση με τον ακόλουθο τρόπο:

	class Photo extends Eloquent {

		public function imageable()
		{
			return $this->morphTo();
		}

	}

	class Staff extends Eloquent {

		public function photos()
		{
			return $this->morphMany('Photo', 'imageable');
		}

	}

	class Order extends Eloquent {

		public function photos()
		{
			return $this->morphMany('Photo', 'imageable');
		}

	}

Τώρα, μπορούμε να ανακτήσουμε τις φωτογραφίες και για ένα staff member και για ένα order:

#### Ανακτώντας μια πολυμορφική συσχέτιση

	$staff = Staff::find(1);

	foreach ($staff->photos as $photo)
	{
		//
	}

#### Ανακτώντας τον ιδιοκτήτη μιας πολυμορφικής συσχέτισης

Παρόλαυτα, η αληθινή μαγεία του "πολυμορφισμού" είναι όταν έχετε πρόσβαση στο staff ή στο order από το μοντέλο `Photo`:

	$photo = Photo::find(1);

	$imageable = $photo->imageable;

Η συσχέτιση `imageable` στο μοντέλο `Photo` θα σας επιστρέψει είτε ένα στιγμιότυπο του μοντέλου `Staff` είτε ένα άλλο του μοντέλου `Order`, ανάλογα σε ποιό είδος μοντέλου ανήκει η φωτογραφία.

Για να καταλάβετε τον τρόπο με τον οποίο γίνεται αυτό, ας εξερευνήσουμε την δομή της βάσης δεδομένων μας σχετικά με τις πολυμορφικές συσχετίσεις:

#### Δομή πίνακα πολυμορφικής συσχέτισης

	staff
		id - integer
		name - string

	orders
		id - integer
		price - integer

	photos
		id - integer
		path - string
		imageable_id - integer
		imageable_type - string

Τα πεδία κλειδί εδώ είναι τα `imageable_id` και `imageable_type` στον πίνακα `photos`. Το ID θα περιέχει την τιμή του ID, και σε αυτό το παράδειγμα, το κατέχον staff ή order, ενώ ο τύπος θα περιέχει το όνομα της κλάσης του μοντέλου. Αυτό είναι που επιτρέπει στο ORM να ορίσει ποιόν τύπο μοντέλου να επιστρέψει όταν έχουμε πρόσβαση στην συσχέτιση `imageable`.

<a name="querying-relations"></a>
## Querying Relations

Όταν έχετε πρόσβαση στις καταγραφές ενός μοντέλου, μπορεί να θέλετε να περιορίσετε τα αποτελέσματά σας βασιζόμενοι στην ύπαρξη μιας συσχέτισης. Για παράδειγμα, μπορεί να θέλετε να ανακτήσετε όλες τις δημοσιεύσεις blog που έχουν τουλάχιστον ένα σχόλιο. Για να το κάνετε αυτό, μπορείτε να χρησιμοποιείσετε την μέθοδο `has`:

#### Querying Relations When Selecting

	$posts = Post::has('comments')->get();

You may also specify an operator and a count:

	$posts = Post::has('comments', '>=', 3)->get();

If you need even more power, you may use the `whereHas` and `orWhereHas` methods to put "where" conditions on your `has` queries:

	$posts = Post::whereHas('comments', function($q)
	{
		$q->where('content', 'like', 'foo%');

	})->get();

<a name="dynamic-properties"></a>
### Dynamic Properties

Eloquent allows you to access your relations via dynamic properties. Eloquent will automatically load the relationship for you, and is even smart enough to know whether to call the `get` (for one-to-many relationships) or `first` (for one-to-one relationships) method.  It will then be accessible via a dynamic property by the same name as the relation. For example, with the following model `$phone`:

	class Phone extends Eloquent {

		public function user()
		{
			return $this->belongsTo('User');
		}

	}

	$phone = Phone::find(1);

Instead of echoing the user's email like this:

	echo $phone->user()->first()->email;

It may be shortened to simply:

	echo $phone->user->email;

> **Note:** Relationships that return many results will return an instance of the `Illuminate\Database\Eloquent\Collection` class.

<a name="eager-loading"></a>
## Eager Loading

Eager loading exists to alleviate the N + 1 query problem. For example, consider a `Book` model that is related to `Author`. The relationship is defined like so:

	class Book extends Eloquent {

		public function author()
		{
			return $this->belongsTo('Author');
		}

	}

Now, consider the following code:

	foreach (Book::all() as $book)
	{
		echo $book->author->name;
	}

This loop will execute 1 query to retrieve all of the books on the table, then another query for each book to retrieve the author. So, if we have 25 books, this loop would run 26 queries.

Thankfully, we can use eager loading to drastically reduce the number of queries. The relationships that should be eager loaded may be specified via the `with` method:

	foreach (Book::with('author')->get() as $book)
	{
		echo $book->author->name;
	}

In the loop above, only two queries will be executed:

	select * from books

	select * from authors where id in (1, 2, 3, 4, 5, ...)

Wise use of eager loading can drastically increase the performance of your application.

Of course, you may eager load multiple relationships at one time:

	$books = Book::with('author', 'publisher')->get();

You may even eager load nested relationships:

	$books = Book::with('author.contacts')->get();

In the example above, the `author` relationship will be eager loaded, and the author's `contacts` relation will also be loaded.

### Eager Load Constraints

Sometimes you may wish to eager load a relationship, but also specify a condition for the eager load. Here's an example:

	$users = User::with(array('posts' => function($query)
	{
		$query->where('title', 'like', '%first%');
	}))->get();

In this example, we're eager loading the user's posts, but only if the post's title column contains the word "first".

### Lazy Eager Loading

It is also possible to eagerly load related models directly from an already existing model collection. This may be useful when dynamically deciding whether to load related models or not, or in combination with caching.

	$books = Book::all();

	$books->load('author', 'publisher');

<a name="inserting-related-models"></a>
## Inserting Related Models

You will often need to insert new related models. For example, you may wish to insert a new comment for a post. Instead of manually setting the `post_id` foreign key on the model, you may insert the new comment from its parent `Post` model directly:

#### Attaching A Related Model

	$comment = new Comment(array('message' => 'A new comment.'));

	$post = Post::find(1);

	$comment = $post->comments()->save($comment);

In this example, the `post_id` field will automatically be set on the inserted comment.

### Associating Models (Belongs To)

When updating a `belongsTo` relationship, you may use the `associate` method. This method will set the foreign key on the child model:

	$account = Account::find(10);

	$user->account()->associate($account);

	$user->save();

### Inserting Related Models (Many To Many)

You may also insert related models when working with many-to-many relations. Let's continue using our `User` and `Role` models as examples. We can easily attach new roles to a user using the `attach` method:

#### Attaching Many To Many Models

	$user = User::find(1);

	$user->roles()->attach(1);

You may also pass an array of attributes that should be stored on the pivot table for the relation:

	$user->roles()->attach(1, array('expires' => $expires));

Of course, the opposite of `attach` is `detach`:

	$user->roles()->detach(1);

You may also use the `sync` method to attach related models. The `sync` method accepts an array of IDs to place on the pivot table. After this operation is complete, only the IDs in the array will be on the intermediate table for the model:

#### Using Sync To Attach Many To Many Models

	$user->roles()->sync(array(1, 2, 3));

You may also associate other pivot table values with the given IDs:

#### Adding Pivot Data When Syncing

	$user->roles()->sync(array(1 => array('expires' => true)));

Sometimes you may wish to create a new related model and attach it in a single command. For this operation, you may use the `save` method:

	$role = new Role(array('name' => 'Editor'));

	User::find(1)->roles()->save($role);

In this example, the new `Role` model will be saved and attached to the user model. You may also pass an array of attributes to place on the joining table for this operation:

	User::find(1)->roles()->save($role, array('expires' => $expires));

<a name="touching-parent-timestamps"></a>
## Touching Parent Timestamps

When a model `belongsTo` another model, such as a `Comment` which belongs to a `Post`, it is often helpful to update the parent's timestamp when the child model is updated. For example, when a `Comment` model is updated, you may want to automatically touch the `updated_at` timestamp of the owning `Post`. Eloquent makes it easy. Just add a `touches` property containing the names of the relationships to the child model:

	class Comment extends Eloquent {

		protected $touches = array('post');

		public function post()
		{
			return $this->belongsTo('Post');
		}

	}

Now, when you update a `Comment`, the owning `Post` will have its `updated_at` column updated:

	$comment = Comment::find(1);

	$comment->text = 'Edit to this comment!';

	$comment->save();

<a name="working-with-pivot-tables"></a>
## Working With Pivot Tables

As you have already learned, working with many-to-many relations requires the presence of an intermediate table. Eloquent provides some very helpful ways of interacting with this table. For example, let's assume our `User` object has many `Role` objects that it is related to. After accessing this relationship, we may access the `pivot` table on the models:

	$user = User::find(1);

	foreach ($user->roles as $role)
	{
		echo $role->pivot->created_at;
	}

Notice that each `Role` model we retrieve is automatically assigned a `pivot` attribute. This attribute contains a model representing the intermediate table, and may be used as any other Eloquent model.

By default, only the keys will be present on the `pivot` object. If your pivot table contains extra attributes, you must specify them when defining the relationship:

	return $this->belongsToMany('Role')->withPivot('foo', 'bar');

Now the `foo` and `bar` attributes will be accessible on our `pivot` object for the `Role` model.

If you want your pivot table to have automatically maintained `created_at` and `updated_at` timestamps, use the `withTimestamps` method on the relationship definition:

	return $this->belongsToMany('Role')->withTimestamps();

To delete all records on the pivot table for a model, you may use the `detach` method:

#### Deleting Records On A Pivot Table

	User::find(1)->roles()->detach();

Note that this operation does not delete records from the `roles` table, but only from the pivot table.

#### Defining A Custom Pivot Model

Laravel also allows you to define a custom Pivot model. To define a custom model, first create your own "Base" model class that extends `Eloquent`. In your other Eloquent models, extend this custom base model instead of the default `Eloquent` base. In your base model, add the following function that returns an instance of your custom Pivot model:

	public function newPivot(Model $parent, array $attributes, $table, $exists)
	{
		return new YourCustomPivot($parent, $attributes, $table, $exists);
	}

<a name="collections"></a>
## Collections

All multi-result sets returned by Eloquent, either via the `get` method or a `relationship`, will return a collection object. This object implements the `IteratorAggregate` PHP interface so it can be iterated over like an array. However, this object also has a variety of other helpful methods for working with result sets.

For example, we may determine if a result set contains a given primary key using the `contains` method:

#### Checking If A Collection Contains A Key

	$roles = User::find(1)->roles;

	if ($roles->contains(2))
	{
		//
	}

Collections may also be converted to an array or JSON:

	$roles = User::find(1)->roles->toArray();

	$roles = User::find(1)->roles->toJson();

If a collection is cast to a string, it will be returned as JSON:

	$roles = (string) User::find(1)->roles;

Eloquent collections also contain a few helpful methods for looping and filtering the items they contain:

#### Iterating Collections

	$roles = $user->roles->each(function($role)
	{
		//
	});

#### Filtering Collections

When filtering collections, the callback provided will be used as callback for [array_filter](http://php.net/manual/en/function.array-filter.php).

	$users = $users->filter(function($user)
	{
		if($user->isAdmin())
		{
			return $user;
		}
	});

> **Note:** When filtering a collection and converting it to JSON, try calling the `values` function first to reset the array's keys.

#### Applying A Callback To Each Collection Object

	$roles = User::find(1)->roles;

	$roles->each(function($role)
	{
		//
	});

#### Sorting A Collection By A Value

	$roles = $roles->sortBy(function($role)
	{
		return $role->created_at;
	});

Sometimes, you may wish to return a custom Collection object with your own added methods. You may specify this on your Eloquent model by overriding the `newCollection` method:

#### Returning A Custom Collection Type

	class User extends Eloquent {

		public function newCollection(array $models = array())
		{
			return new CustomCollection($models);
		}

	}

<a name="accessors-and-mutators"></a>
## Accessors & Mutators

Eloquent provides a convenient way to transform your model attributes when getting or setting them. Simply define a `getFooAttribute` method on your model to declare an accessor. Keep in mind that the methods should follow camel-casing, even though your database columns are snake-case:

#### Defining An Accessor

	class User extends Eloquent {

		public function getFirstNameAttribute($value)
		{
			return ucfirst($value);
		}

	}

In the example above, the `first_name` column has an accessor. Note that the value of the attribute is passed to the accessor.

Mutators are declared in a similar fashion:

#### Defining A Mutator

	class User extends Eloquent {

		public function setFirstNameAttribute($value)
		{
			$this->attributes['first_name'] = strtolower($value);
		}

	}

<a name="date-mutators"></a>
## Date Mutators

By default, Eloquent will convert the `created_at`, `updated_at`, and `deleted_at` columns to instances of [Carbon](https://github.com/briannesbitt/Carbon), which provides an assortment of helpful methods, and extends the native PHP `DateTime` class.

You may customize which fields are automatically mutated, and even completely disable this mutation, by overriding the `getDates` method of the model:

	public function getDates()
	{
		return array('created_at');
	}

When a column is considered a date, you may set its value to a UNIX timetamp, date string (`Y-m-d`), date-time string, and of course a `DateTime` / `Carbon` instance.

To totally disable date mutations, simply return an empty array from the `getDates` method:

	public function getDates()
	{
		return array();
	}

<a name="model-events"></a>
## Model Events

Eloquent models fire several events, allowing you to hook into various points in the model's lifecycle using the following methods: `creating`, `created`, `updating`, `updated`, `saving`, `saved`, `deleting`, `deleted`, `restoring`, `restored`.

Whenever a new item is saved for the first time, the `creating` and `created` events will fire. If an item is not new and the `save` method is called, the `updating` / `updated` events will fire. In both cases, the `saving` / `saved` events will fire.

If `false` is returned from the `creating`, `updating`, `saving`, or `deleting` events, the action will be cancelled:

#### Cancelling Save Operations Via Events

	User::creating(function($user)
	{
		if ( ! $user->isValid()) return false;
	});

Eloquent models also contain a static `boot` method, which may provide a convenient place to register your event bindings.

#### Setting A Model Boot Method

	class User extends Eloquent {

		public static function boot()
		{
			parent::boot();

			// Setup event bindings...
		}

	}

<a name="model-observers"></a>
## Model Observers

To consolidate the handling of model events, you may register a model observer. An observer class may have methods that correspond to the various model events. For example, `creating`, `updating`, `saving` methods may be on an observer, in addition to any other model event name.

So, for example, a model observer might look like this:

	class UserObserver {

		public function saving($model)
		{
			//
		}

		public function saved($model)
		{
			//
		}

	}

You may register an observer instance using the `observe` method:

	User::observe(new UserObserver);

<a name="converting-to-arrays-or-json"></a>
## Converting To Arrays / JSON

When building JSON APIs, you may often need to convert your models and relationships to arrays or JSON. So, Eloquent includes methods for doing so. To convert a model and its loaded relationship to an array, you may use the `toArray` method:

#### Converting A Model To An Array

	$user = User::with('roles')->first();

	return $user->toArray();

Note that entire collections of models may also be converted to arrays:

	return User::all()->toArray();

To convert a model to JSON, you may use the `toJson` method:

#### Converting A Model To JSON

	return User::find(1)->toJson();

Note that when a model or collection is cast to a string, it will be converted to JSON, meaning you can return Eloquent objects directly from your application's routes!

#### Returning A Model From A Route

	Route::get('users', function()
	{
		return User::all();
	});

Sometimes you may wish to limit the attributes that are included in your model's array or JSON form, such as passwords. To do so, add a `hidden` property definition to your model:

#### Hiding Attributes From Array Or JSON Conversion

	class User extends Eloquent {

		protected $hidden = array('password');

	}

> **Note:** When hiding relationships, use the relationship's **method** name, not the dynamic accessor name.

Alternatively, you may use the `visible` property to define a white-list:

	protected $visible = array('first_name', 'last_name');

<a name="array-appends"></a>
Occasionally, you may need to add array attributes that do not have a corresponding column in your database. To do so, simply define an accessor for the value:

	public function getIsAdminAttribute()
	{
		return $this->attributes['admin'] == 'yes';
	}

Once you have created the accessor, just add the value to the `appends` property on the model:

	protected $appends = array('is_admin');

Once the attribute has been added to the `appends` list, it will be included in both the model's array and JSON forms.
