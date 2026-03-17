-- 0. drop existing tables to start fresh
DROP TABLE IF EXISTS quotes;
DROP TABLE IF EXISTS authors;
DROP TABLE IF EXISTS categories;

-- 1. create authors table
CREATE TABLE authors (
    id SERIAL PRIMARY KEY,
    author VARCHAR(255) NOT NULL
);

-- 2. create categories table
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    category VARCHAR(255) NOT NULL
);

-- 3. create quotes table
CREATE TABLE quotes (
    id SERIAL PRIMARY KEY,
    quote TEXT NOT NULL,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- 4. insert categories
INSERT INTO categories (category) VALUES 
('Music'), ('Sports'), ('Funny'), ('Random'), ('Inspirational');

-- 5. insert authors
INSERT INTO authors (author) VALUES 
('Bob Marley'), ('Jimi Hendrix'), ('Michael Jordan'), ('Muhammad Ali'), 
('Wayne Gretzky'), ('Mark Twain'), ('Oscar Wilde'), ('Will Rogers'), 
('Albert Einstein'), ('Dolly Parton'), ('Freddie Mercury'), ('Kurt Cobain'), 
('Taylor Swift'), ('Kobe Bryant'), ('Yogi Berra'), ('Mitch Hedberg'), 
('Steven Wright'), ('Groucho Marx'), ('Unknown');

-- 6. insert quotes (35 quotes total)
INSERT INTO quotes (quote, author_id, category_id) VALUES 
('One good thing about music, when it hits you, you feel no pain.', 1, 1),
('Music is my religion.', 2, 1),
('I can accept failure, everyone fails at something. But I can''t accept not trying.', 3, 2),
('Don''t count the days, make the days count.', 4, 2),
('You miss 100% of the shots you don''t take.', 5, 2),
('Whenever you find yourself on the side of the majority, it is time to pause and reflect.', 6, 4),
('Be yourself; everyone else is already taken.', 7, 4),
('Everything is funny, as long as it is happening to somebody else.', 8, 3),
('Imagination is more important than knowledge.', 9, 4),
('The way I see it, if you want the rainbow, you gotta put up with the rain.', 10, 1),
('I won''t be a rock star. I will be a legend.', 11, 1),
('The duty of youth is to challenge corruption.', 12, 1),
('Happiness is only real when shared.', 13, 4),
('Everything negative - pressure, challenges - is all an opportunity for me to rise.', 14, 2),
('It ain''t over till it''s over.', 15, 3),
('I haven''t slept for ten days, because that would be too long.', 16, 3),
('I intend to live forever. So far, so good.', 17, 3),
('I find television very educating. Every time somebody turns it on, I go into the other room and read a book.', 18, 3),
('A day without sunshine is like, you know, night.', 17, 3),
('Life is what happens when you''re busy making other plans.', 19, 4),
('Without music, life would be a mistake.', 19, 1),
('If you''re going through hell, keep going.', 19, 4),
('People say nothing is impossible, but I do nothing every day.', 19, 3),
('Hard work beats talent when talent doesn''t work hard.', 19, 2),
('Music can change the world because it can change people.', 1, 1),
('I''ve failed over and over again in my life. And that is why I succeed.', 3, 2),
('The best way to predict the future is to create it.', 19, 4),
('I''m not superstitious, but I am a little stitious.', 19, 3),
('Float like a butterfly, sting like a bee.', 4, 2),
('Where words fail, music speaks.', 19, 1),
('Keep your face always toward the sunshine - and shadows will fall behind you.', 19, 5),
('The only way to do great work is to love what you do.', 19, 5),
('Believe you can and you''re halfway there.', 19, 5),
('It always seems impossible until it is done.', 19, 5),
('The only limit to our realization of tomorrow will be our doubts of today.', 19, 5);