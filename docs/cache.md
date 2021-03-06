# Μνήμη Cache

- [Διαμόρφωση](#configuration)
- [Χρήση μνήμης Cache](#cache-usage)
- [Αυξήσεις & Μειώσεις](#increments-and-decrements)
- [Ετικέτες μνήμης Cache](#cache-tags)
- [Βάση δεδομένων μνήμης Cache](#database-cache)

<a name="configuration"></a>
## Διαμόρφωση

Το Laravel παρέχει ένα ενοποιημένο API (διεπαφή προγραμματισμού εφαρμογών) για διάφορα συστήματα που κάνουν χρήση της μνήμης cache. Για να διαμορφώσετε τις επιλογές της μνήμης cache, πηγαίνετε στο αρχείο `app/config/cache.php`. Σε αυτό το αρχείο μπορείτε να καθορίσετε τον οδηγό της μνήμης cache που θέλετε να χρησιμοποιηθεί ως προεπιλογή για την εφαρμογή σας. Το Laravel υποστηρίζει δημοφιλείς συστήματα μνήμης cache όπως το [Memcached](http://memcached.org) και το [Redis](http://redis.io) από την αρχή της εγκατάστασής του.

Το αρχείο διαμόρφωσης επιλογών της μνήμης cache περιλαμβάνει επίσης διάφορες άλλες παραμετροποιήσιμες επιλογές, οπότε βεβαιωθείτε ότι διαβάσατε τον σχολιασμό τους για να τις κατανοήσετε. Από προεπιλογή, το Laravel είναι διαμορφωμένο να χρησιμοποιεί την επιλογή `αρχείο` (`file`) ως οδηγό μνήμης cache, το οποίο αποθηκεύει τα αντικείμενα της μνήμης cache στο σύστημα αρχείων. Για μεγαλύτερες εφαρμογές, προτείνεται η χρήση in-memory μνήμης cache όπως για παράδειγμα Memcached ή APC.

<a name="cache-usage"></a>
## Χρήση μνήμης Cache

#### Αποθηκεύοντας ένα αντικείμενο μέσα στην μνήμη Cache

	Cache::put('key', 'value', $minutes);

#### Χρησιμοποιώντας αντικείμενα Carbon για τον ορισμό του χρόνου λήξης

	$expiresAt = Carbon::now()->addMinutes(10);

	Cache::put('key', 'value', $expiresAt);

#### Αποθηκεύοντας ένα αντικείμενο μέσα στην μνήμη Cache αν αυτό δεν υπάρχει ήδη

	Cache::add('key', 'value', $minutes);

Η μέθοδος `προσθήκη` (`add`) θα επιστρέψει `αληθές` (`true`) εάν το στοιχείο πράγματι **προστέθηκε** στην μνήμη cache. Σε αντίθετη περίπτωση, η μέθοδος θα επιστρέψει `ψευδής` (`false`).

#### Ελέγχοντας για ύπαρξη στοιχείου μέσα στην μνήμη Cache

	if (Cache::has('key'))
	{
		//
	}

#### Ανάκτηση ενός στοιχείου από την μνήμη Cache

	$value = Cache::get('key');

#### Ανάκτηση ενός στοιχείου ή επιστροφή μιας προεπιλεγμένης τιμής

	$value = Cache::get('key', 'default');

	$value = Cache::get('key', function() { return 'default'; });

#### Αποθηκεύοντας ένα αντικείμενο μέσα στην μνήμη Cache μόνιμα

	Cache::forever('key', 'value');

Μερικές φορές μπορεί να εύχεστε να ανακτήσετε ένα στοιχείο από την μνήμη cache, αλλά να αποθηκεύσετε και μία προεπιλεγμένη τιμή αν η ζητούμενη τιμή δεν υπάρχει ήδη. Μπορείτε να το κάνετε αυτό με την χρήση της μεθόδου `Cache::remember`:

	$value = Cache::remember('users', $minutes, function()
	{
		return DB::table('users')->get();
	});

Μπορείτε επίσης να συνδυάσετε τις μεθόδους `remember` και `forever`:

	$value = Cache::rememberForever('users', function()
	{
		return DB::table('users')->get();
	});

Σημειώστε ότι όλα τα στοιχεία που αποθηκεύονται στην μνήμη cache γίνονται serialized, και έτσι είσαστε ελεύθεροι να αποθηκεύσετε οποιοδήποτε τύπο δεδομένων εσείς επιλέξετε.

#### Αφαιρώντας ένα στοιχείο από την μνήμη Cache

	Cache::forget('key');

<a name="increments-and-decrements"></a>
## Αυξήσεις & Μειώσεις

Όλοι οι οδηγοί εκτός από τους οδηγούς `αρχείο` και `βάση δεδομένων` (`file` and `database`) υποστηρίζουν τις λειτουργίες `αύξησης` και `μείωσης` (`increment` and `decrement`):

#### Αυξάνοντας μια τιμή

	Cache::increment('key');

	Cache::increment('key', $amount);

#### Μειώνοντας μια τιμή

	Cache::decrement('key');

	Cache::decrement('key', $amount);

<a name="cache-tags"></a>
## Ετικέτες μνήμης Cache

> **Σημείωση:** Οι ετικέτες μνήμης Cache δεν υποστηρίζονται όταν γίνεται χρήση των οδηγών μνήμης cache `αρχείο` και `βάση δεδομένων` (`file` or `database`). Επιπλέον, όταν γίνεται χρήση πολλαπλών ετικετών που αποθηκεύονται "για πάντα" ("forever"), η απόδοση θα είναι καλύτερη με την χρήση ενός οδηγού μνήμης cache όπως για παράδειγμα `memcached`, ο οποίος κάνει αυτόματη εκκαθάριση στις παλαιές εγγραφές της μνήμης.

Οι ετικέτες της μνήμης Cache σας επιτρέπουν να επισημάνετε σχετικά στοιχεία μέσα στην μνήμη, και έπειτα να απαλείψετε όλη την μνήμη cache που έχει επισημανθεί με ένα συγκεκριμένο όνομα. Για να έχετε πρόσβαση σε μια επισυνημμένη με ετικέτα μνήμη cache, χρησιμοποιείστε την μέθοδο `ετικέτες` (`tags`):

#### Έχοντας πρόσβαση σε επισυνημμένη με ετικέτα μνήμη Cache

Μπορείτε να αποθηκεύσετε μια επισυνημμένη με ετικέτα μνήμη cache απλά περνώντας σε μια διατεταγμένη λίστα ονόματα ετικετών ως παραμέτρους, ή ως έναν διατεταγμένο πίνακα αποτελούμενο από ονόματα ετικετών.

	Cache::tags('people', 'authors')->put('John', $john, $minutes);

	Cache::tags(array('people', 'artists'))->put('Anne', $anne, $minutes);

Μπορείτε να χρησιμοποιήσετε οποιαδήποτε μέθοδο αποθήκευσης μνήμης cache σε συνδυασμό με ετικέτες, συμπεριλαμβανομένου των μεθόδων `θυμάμαι`, `για πάντα`, και `θυμάμαι για πάντα` (`remember`, `forever`, and `rememberForever`). Μπορείτε επίσης να έχετε πρόσβαση σε στοιχεία από την επισυνημμένη μνήμη cache, όπως επίσης και να χρησιμοποιήσετε τις άλλες μεθόδους της μνήμης cache όπως για παράδειγμα τις μεθόδους `αύξηση` και `μείωση` (`increment` and `decrement`):

#### Έχοντας πρόσβαση σε στοιχεία μιας επισυνημμένης με ετικέτα μνήμης Cache

Για να έχετε πρόσβαση σε μια επισυνημμένη με ετικέτα μνήμη cache, περάστε ως παράμετρο την ίδια διατεταγμένη λίστα από ετικέτες που χρησιμοποιήσατε για να την αποθηκεύσετε.

	$anne = Cache::tags('people', 'artists')->get('Anne');

	$john = Cache::tags(array('people', 'authors'))->get('John');

Μπορείτε να διαγράψετε όλα τα επισυνημμένα με ετικέτα στοιχεία που αποτελούνται από ένα όνομα ή μια λίστα από ονόματα. Για παράδειγμα, αυτή η δήλωση θα αφαιρέσει όλα τα αποθηκευμένα στοιχεία της μνήμης cache που έχουν επισυναφθεί με μια από τις ετικέτες `people`, `authors`, ή και με τις δύο. Έτσι, αμφότεροι οι "Anne" και ο "John" θα αφαιρεθούν από την μνήμη cache:

	Cache::tags('people', 'authors')->flush();

Σε αντίθεση, αυτή η δήλωση θα αφαιρέσει μόνο τις επισυνημμένες με ετικέτα μνήμες cache που κάνουν χρήση της ετικέτας `authors`, άρα ο "John" θα αφαιρεθεί, αλλά η "Anne" όχι.

	Cache::tags('authors')->flush();

<a name="database-cache"></a>
## Βάση δεδομένων μνήμης Cache

Όταν χρησιμοποιείτε τον οδηγό μνήμης cache `βάση δεδομένων`, θα χρειαστεί να φτιάξετε ένα πίνακα για να περιέχει όλα τα στοιχεία της μνήμης αυτής. Θα βρείτε ένα παράδειγμα με την ονομασία `Schema` για τον πίνακα αυτό παρακάτω:

	Schema::create('cache', function($table)
	{
		$table->string('key')->unique();
		$table->text('value');
		$table->integer('expiration');
	});
