require 'CSV'

places = CSV.read(File.dirname(__FILE__) << '../data/extension1.csv')
places.shift

sql = 'INSERT INTO location (name, streetaddress1, streetaddress2, city, state, zip, phone, standard, display_order) VALUES '

values = places.map do |place|
	place.map!{|col| '"' + col.to_s + '"'}
	"(#{[place[1], place[2], place[3], place[4], place[5], place[6], place[7], 1, 1].join(',')})"
end.join(',')

sql = sql << values << ';'

puts sql

places2 = CSV.read(File.dirname(__FILE__) << '../data/extension2.csv')
places2.shift

sql2 = 'INSERT INTO location (name, streetaddress1, streetaddress2, city, state, zip, phone, standard, display_order) VALUES '

values = places2.map do |place|
	place.map!{|col| '"' + col.to_s + '"'}
	"(#{[place[1], place[2], place[3], place[4], place[5], place[6], place[7], 1, 1].join(',')})"
end.join(',')

sql2 = sql2 << values << ';'

puts sql2

places3 = CSV.read(File.dirname(__FILE__) << '../data/extension3.csv')
places3.shift

sql3 = 'INSERT INTO location (name, streetaddress1, streetaddress2, city, state, zip, phone, standard, display_order) VALUES '

values = places3.map do |place|
	place.map!{|col| '"' + col.to_s + '"'}
	"(#{[place[1], place[4], place[5], place[7], place[8], place[9], place[10], 1, 1].join(',')})"
end.join(',')

sql3 = sql3 << values << ';'

puts sql3

